<?	session_start();
    require_once '../config.php';
    require_once 'connect_db.php';
    
    $Db=new Db();
	$connect=$Db->getConnect();
	    
    /**
     * Проверить уникальность записи (логин, емэйл)
     */
    function checkSingleField($field){
        global $connect,$test;
        return $connect->query("SELECT COUNT(*) 
                FROM users WHERE $field = '$_POST[$field]'")
                       ->fetchColumn();       
    }
    /**
     * Если не можем получить last_insert_id прямо (к сожалению, такое не исключено):
     */
    function getLastId($table='users'){
        global $connect,$test;
        return $connect->query("SELECT MAX(id) FROM $table")->fetchColumn();
    }
    
    // создать массивы неправильных значений
    // (невалидные, занятые, проблемные)
    $Invalids=explode(",",Invalids); //'invalids,taken,xtra'
    foreach ($Invalids as $invld){
        ${$invld} = array(); //$invalids, $taken, $xtra        
        unset($_SESSION[$invld]); // удалить старые данные, если остались
    }
    unset($_SESSION['valid_data']);
    
    // контейнер данных для добавления в таблицу:
    $dataToInsert=array();
    foreach ($_POST as $key => $val){        
        switch ($key){
            case 'login':
                // проверить логин на уникальность:
                if (checkSingleField('login')){
                    $taken[$key]=$val; // пополнить массив занятых значений                    
                }else{
                    if(preg_match($filters[$key], $val)){
                        $invalids[$key]=$val; // пополнить массив невалидных значений
                    }else{ // пополнить массив валидных значений для добавления в таблицу users
                        $dataToInsert[$key]=$val;
                    }
                }
                break;
            case 'password'://password2
                if(preg_match($filters[$key], $val)){
                    $invalids[$key]=$val;
                }else{
                    $dataToInsert[$key] = md5($val);
                }
                if(preg_match($filters[$key], $_POST['password2'])){
                    $invalids[$key.'2']=$_POST['password2'];
                }
                if($val!=$_POST['password2']) // пополнить массив дополнительнрых невалидных значений
                    $xtra['pass_diff']=PassDiff;                 
                break;
            case 'email':
                // проверить на занятость и валидность:
                if (checkSingleField('email')){
                    $taken[$key]=$val;
                }else{
                    if(!filter_var($val, FILTER_VALIDATE_EMAIL)){
                        $invalids[$key]=$val;
                    }else{
                        $dataToInsert[$key]=$val;
                    }
                }
                break;
            case 'name': 
                if(preg_match($filters[$key], $val)){
                    $invalids[$key]=$val;
                }else{
                    $dataToInsert[$key]=$val;
                }
            break;
            case 'phone':
                if(preg_match($filters[$key], $val)){
                    $invalids[$key]=$val;
                }else{
                    $dataToInsert[$key]=$val;
                }
            break;
        } 
    }    
    /**
     *  Выяснить, является ли валидным расширение загруженного файла. 
     *  Если нет, также будем возвращаться назад - к форме заполнения данных
     */
    if($file_data=$_FILES['pic']){
        // проверим расширение загруженного файла:
        $arr_fname=explode(".",$file_data['name']);
        $ext = array_pop($arr_fname);
        if(!in_array($ext, explode(",",PicExt))){
            $xtra['wrong_pic']=WrongPicExt; // дополним один из массивов невалидных полей
        }
    }
    
    $wrng=0; // счётчик ошибок
    // сохранить невалидные данные в сессии, чтобы показать юзеру при возврате
    foreach($Invalids as $inv) { // 'invalids','taken','xtra'
        if(!empty(${$inv})){ // $invalids, $taken, $xtra            
            $_SESSION[$inv]=${$inv};
            $wrng++;            
        }
    }
    // если никаких ошибок не выявлено, будем добавлять записи в таблицу:        
    if(!$wrng){
        $keys=$vals=array();
        foreach($dataToInsert as $field=>$value){
            $keys[]=$field;
            $vals[]=$value;
        }
        // сформируем SQL-запрос:
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
        // если всё ОК и есть файлы, будем размещать их:        
        if($file_data&&!isset($xtra['wrong_pic'])){ 
            /*  заменим все подозрительные символы в имени файла.
                Это нужно нам только для информативных целей. Сам файл будет 
                сохранён под именем последней добавленной в таблицу записи */
            $file_name = preg_replace("/[^а-пр-яА-ПР-Яa-zA-Z0-9\-\._]/", "", $file_data['name']);
            $query="INSERT INTO pix (filename,user_id) VALUES ('$file_name',".$last_id.")";
            try{
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
            того, как невалидные данные были обнаружены. Потому что:
            *   юзер должен знать, что он указал неправильно (это очевидно, да); 
            *   система должна назначать соответствующий стилевой атрибут 
                невалидным данным. 
            т.о., мы подставляем в ячейки как валидные, так и НЕ валидные данные,
            но последние должны быть выделены цветом. 
            Но (снова) это только в случае, если они (последние) есть. 
            Т.е., это - условие следующего блока.
         */
        unset($_SESSION['valid_data']);
        // директимся после успешной регистрации:
        header("location: ".SITE_ROOT."account?register=ok");        
    }else{  // всё не так хорошо, как хотелось бы :)        
        /*  удалить из сессии и массивов валидных данных пароли, поскольку:
            - пароль уже обработан функцией md5()
            - пароли при возврате (к форме) не должны отображаться всё равно */
        unset($dataToInsert['password']);
        unset($dataToInsert['password2']);
        unset($_SESSION['invalid']['password']);
        unset($_SESSION['invalid']['password2']);
        // сохраним валидные значения для подстановки в поля формы:
        // (невалидные уже сохранены)
        $_SESSION['valid_data']=$dataToInsert;
        // директимся после провала миссии:
        header("location: ".SITE_ROOT."return");
    }
