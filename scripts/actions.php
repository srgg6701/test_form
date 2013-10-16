<?	session_start();
    require_once '../config.php';
    require_once 'connect_db.php';
    
    $test=true;
	if($test){
        var_dump($_POST);
        var_dump($_FILES['pic']);
    }
    /**
     * Проверить уникальность записи (логин, емэйл)
     */
    function checkSingleField($field){
        global $connect,$test;
        $cnt = $connect->query("SELECT COUNT(*) 
                FROM users WHERE $field = '$_POST[$field]'")
                       ->fetchColumn();
        if($cnt&&$test)
            echo "<div>Запись НЕ уникальна. Всего записей: $cnt</div>";        
        return $cnt;
    }
    /**
     * Если не можем получить last_insert_id прямо:
     */
    function getLastId($table='users'){
        global $connect,$test;
        return $connect->query("SELECT MAX(id) FROM $table")->fetchColumn();
    }
    /**
     * Вывести результат валидации в тестовом режиме:
     */
    function showTestValidationResult($key,$val,$valid=false){
        global $test;
        if($test) {
            echo "<div>Данные ";
            if(!$valid) echo " <b style='color:red'>НЕ</b> ";
            echo " валидны:<br><b>$key</b> => $val</div>";
        }
    }
    
    $Db=new Db();
	$connect=$Db->getConnect();
	
    // создать массивы неправильных значений
    // (невалидные, занятые, проблемные)
    $Invalids=explode(",",Invalids); //'invalids,taken,xtra'
    foreach ($Invalids as $invld){
        ${$invld} = array(); //$invalids, $taken, $xtra        
        unset($_SESSION[$invld]); // удалить старые данные, если остались
    }
    unset($_SESSION['valid_data']);
    //echo "<h1>SESSION:</h1>";
    //var_dump($_SESSION);
    //echo "<hr>";
    // контейнер данных для добавления в таблицу:
    $dataToInsert=array();
    foreach ($_POST as $key => $val){        
        switch ($key){
            case 'login':
                // проверить логин на уникальность:
                if (checkSingleField('login')){
                    $taken[$key]=$val;                    
                }else{
                    if(preg_match($filters[$key], $val)){
                        $invalids[$key]=$val;
                        showTestValidationResult($key,$val);
                    }else{
                        $dataToInsert[$key]=$val;
                        showTestValidationResult($key,$val,true);
                    }
                }
                break;
            case 'password'://password2
                //
                if(preg_match($filters[$key], $val)){
                    $invalids[$key]=$val;
                    showTestValidationResult($key,$val);
                }else{
                    $dataToInsert[$key] = md5($val);
                    showTestValidationResult($key,md5($val),true);
                }
                if(preg_match($filters[$key], $_POST['password2'])){
                    $invalids[$key.'2']=$_POST['password2'];
                    showTestValidationResult($key.'2',$_POST['password2']);
                }
                if($val!=$_POST['password2'])
                    $xtra['pass_diff']=PassDiff;
                
                break;
            case 'email':
                // проверить на занятость и валидность:
                if (checkSingleField('email')){
                    $taken[$key]=$val;
                }else{
                    if(!filter_var($val, FILTER_VALIDATE_EMAIL)){
                        $invalids[$key]=$val;   
                        showTestValidationResult($key,$val);
                    }else{
                        $dataToInsert[$key]=$val;
                        showTestValidationResult($key,$val, true);
                    }
                }
                break;
            case 'name': 
                if(preg_match($filters[$key], $val)){
                    $invalids[$key]=$val;
                    showTestValidationResult($key,$val);
                }else{
                    $dataToInsert[$key]=$val;
                    showTestValidationResult($key,$val, true);
                }
            break;
            case 'phone':
                //
                if(preg_match($filters[$key], $val)){
                    $invalids[$key]=$val;
                    showTestValidationResult($key,$val);
                }else{
                    //$val = str_replace(" ", "", $val);
                    $dataToInsert[$key]=$val;
                    showTestValidationResult($key,$val, true);
                }
            break;
        } 
    }    
    /**
     *  Выяснить, является ли валидным расширение загруженного файла. 
     *  Если нет, будем возвращаться назад - к форме заполнения данных
     */
    if($file_data=$_FILES['pic']){
        // проверим расширение загруженного файла:
        $arr_fname=explode(".",$file_data['name']);
        $ext = array_pop($arr_fname);
        if(!in_array($ext, explode(",",PicExt))){
            $xtra['wrong_pic']=WrongPicExt; // дополним один из массивов невалидных полей
            echo "<h2>ext = $ext</h2>";
        }
    }
    
    $wrng=0;
    // сохранить невалидные данные в сессии, чтобы показать юзеру при возврате
    // или удалить старые данные из сессии, если всё ОК.
    foreach($Invalids as $inv) { // 'invalids','taken','xtra'
        if(!empty(${$inv})){ // $invalids, $taken, $xtra
            if($test) {
                echo "<h4>line ".__LINE__.": Невалидные данные ($inv):</h4>";
                var_dump(${$inv});
            }
            
            $_SESSION[$inv]=${$inv};
            $wrng++;
            
            if(!( $inv=='invalids'
                   && (isset($invalids['password'])||isset($invalids['password2']))
              )) $_SESSION[$inv]=${$inv};
            
            echo '<h3>invalid type: '.$inv.'</h3>';
            var_dump($_SESSION[$inv]);
            var_dump(${$inv});
        }
    }
    // если никаких ошибок не выявлено, будем добавлять записи в таблицу:        
    if(!$wrng){
        $keys=$vals=array();
        foreach($dataToInsert as $field=>$value){
            $keys[]=$field;
            $vals[]=$value;
        }
        $fields='`'.implode('`, `',$keys).'`';
        $values="'".implode("', '",$vals)."'";
        $query="INSERT INTO users (".$fields.", `datetime`) 
                VALUES (".$values.", '".date('Y-m-d H:i:s')."')";
        try{
            if($test) echo "<pre>$query</pre>";
            $Db->execute($query);            
        }catch(PDOExecption $e){
            echo 'Ошибка добавления записи: '.$e->getMessage();
            die();
        }
        // получить id последней добавленной записи в таблицу юзеров, чтобы далее
        // ассоциировать с ним файл, данные которого сохранены в таблице pix:
        if(!$last_id=$Db->getConnect()->lastInsertId()){
            $_SESSION['user_id']=$last_id=getLastId();            
        }
                
        if($file_data&&!isset($xtra['wrong_pic'])){ // если всё ОК и есть файлы, будем размещать их:
            /*  заменим все подозрительные символы в имени файла.
                Это нужно нам только для информативных целей. Сам файл будет 
                сохранён под именем последней добавленной в таблицу записи */
            $file_name = preg_replace("/[^а-яёА-ЯЁa-zA-Z0-9\-\._]/", "", $file_data['name']);
            $query="INSERT INTO pix (filename,user_id) VALUES ('$file_name',".$last_id.")";
            try{
                if($test) echo "<pre>$query</pre>";
                $Db->execute($query);
            }catch(PDOExecption $e){
                echo 'Ошибка добавления записи: '.$e->getMessage();
                die();
            }
            if(!$last_id=$Db->getConnect()->lastInsertId()){
                $last_id=getLastId();
            }
            $file_location = dirname(__FILE__).'/../content/files/'.$last_id.'.'.$ext;            
            // сохранить файл под индексом записи для него в таблице pix
            if (!move_uploaded_file($file_data['tmp_name'], $file_location )) 
                echo "<div style='color:orange'>...Ошибка загрузки файла $file_location</div>";

        }
        /*  удалим ранее сохранённые валидные данные (если есть).
            Они создаются в случае, если есть также и НЕвалидные данные.
            Это нужно для того, чтобы заполнить поля формы при возврате после
            того, как невалидные данные были обнаружены. Потому что система
            должна назначать соответствующий стилевой атрибут невалидным данным,
            т.о., при заполнении ячеек мы должны знать, какие из сохранённых в
            сессии валидны, а какие - нет. */
        unset($_SESSION['valid_data']);
        //var_dump($_SESSION);
        header("location: ".SITE_ROOT."account?register=ok");        
    }else{        
        /*  удалить из сессии и массивов валидных данных пароли, поскольку:
            - пароль уже обработан функцией md5()
            - пароли при возврате (к форме) не сохраняют */
        unset($dataToInsert['password']);
        unset($dataToInsert['password2']);
        unset($_SESSION['invalid']['password']);
        unset($_SESSION['invalid']['password2']);
        
        $_SESSION['valid_data']=$dataToInsert;
        if($test){
            echo "<h4>line ".__LINE__.": Невалидные данные:</h4>";
            foreach($Invalids as $inv) // 'invalids','taken','xtra'
                if(isset($_SESSION[$inv])){
                    echo "<div><b>{$inv}:</b></div>
                    <h3 style='color:green'>Сохранено в сессии:</h3>";
                    var_dump($_SESSION[$inv]);
                }
            echo "<div>redirect: <a href=\"".SITE_ROOT."return\">".SITE_ROOT."return</a></div><hr>";
        }else
            header("location: ".SITE_ROOT."return");
    }
