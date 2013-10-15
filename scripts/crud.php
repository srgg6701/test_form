<?	require_once 'connect_db.php';

	var_dump($post);
    
    /*  array
  'login' => string 'Logos' (length=5)
  'password' => string 'history' (length=7)
  'password2' => string 'history' (length=7)
  'email' => string 'logos@mail.ru' (length=13)
  'name' => string 'Серя' (length=8)
  'phone' => string '89044428447' (length=11)
  'btnSubmit' => string 'Sign up' (length=7)
array
  'name' => string 'mysql_error.gif' (length=15)
  'type' => string 'image/gif' (length=9)
  'tmp_name' => string 'Z:\tmp\phpCA64.tmp' (length=18)
  'error' => int 0
  'size' => int 15161
     
     
     
     
     */
    
    function checkSingleField($field){
        global $connect;
        return $connect->query("SELECT COUNT(*) 
                FROM users WHERE $field = '$_POST[$field]'")
                       ->fetchColumn();
    }
    
    $Db=new Db();
	$connect=$Db->getConnect();
	
    // создать массивы неправильных значений
    $invlds=array('invalids','taken','xtra');
    // (невалидные, занятые, проблемные)
    foreach ($invlds as $invld)
        ${$invld} = array();
    // контейнер данных для добавления в таблицу:
    $dataToInsert=array();
    foreach ($post as $key => $val){        
        switch ($key){
            case 'login':
                // проверить логин на уникальность:
                if (checkSingleField('login'))
                    $taken[$key]=$val;
                else{
                    if(!preg_match($filters[$key], $val))
                        $invalids[$key]=$val;
                    else
                        $dataToInsert[$key]=$val;
                }
                break;
            case 'password'://password2
                //
                if(!preg_match($filters[$key], $val))
                    $invalids[$key]=$val;
                else
                    $dataToInsert[$key]=  md5($val);
                
                if(!preg_match($filters[$key], $_POST['password2']))
                    $invalids[$key.'2']=$_POST['password2'];
                if($val!=$_POST['password2'])
                    $xtra[]=PassDiff;
                break;
            case 'email':
                // проверить на занятость и валидность:
                if (checkSingleField('email')){
                    $taken[$key]=$val;
                }else{
                    if(!filter_var($val, FILTER_VALIDATE_EMAIL))
                        $invalids[$key]=$val;   
                    else
                        $dataToInsert[$key]=$val;
                }
                break;
            case 'name': case 'phone':
                //
                if(!preg_match($filters[$key], $val))
                    $invalids[$key]=$val;
                else{
                    if($key=='phone') // удалить пробелы в № тел.
                        $val = str_replace(" ", "", $val);
                    $dataToInsert[$key]=$val;
                }
                break;
            /*case 'phone':
                //
                if(!preg_match($filters[$key], $val))
                    $invalids[]=$key;
                break;*/
        } 
    }
    $wrng=0;
    // сохранить невалидные данные в сессии, чтобы показать юзеру при возврате
    // или удалить старые данные из сессии, если всё ОК.
    foreach($invlds as $inv) // 'invalids','taken','xtra'
        if(!empty(${$inv})){
            $wrng++;
            $_SESSION[$inv]=${$inv};
        }else
            unset($_SESSION[$inv]);
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
        if(!$rows=$Db->execute($query)){
            echo "<div style='color:red'>Ошибка добавления записи...</div>";
        }
        if($_FILES){ // если всё ОК и есть файлы, будем размещать их:
            foreach($_FILES as $i=>$file)
                var_dump($file);
        }
    }
