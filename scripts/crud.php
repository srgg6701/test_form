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
    
    $Db=new Db();
	$Db->getConnect();
	 
    if(!$result=$Db->query($query)->fetchAll(PDO::FETCH_ASSOC)){
			$this->showError($Db);	
			
	}
    
    foreach ($post as $key => $val){        
        switch ($key){
            case 'login':
                // проверить логин на уникальность:
                if ($Db->query("SELECT COUNT(*) FROM users WHERE login = '$post[login]]'")
                       ->fetchColumn())
                    echo "LOGIN IS TAKEN!";
                echo "Логин свободен!";
                break;
            case 'password'://password2
                //
                break;
            case 'email':
                //
                break;
            case 'name':
                //
                break;
            case 'phone':
                //
                break;
            case '':
                //
                break;
        } 
    }
    
	
    if($_FILES){
		foreach($_FILES as $i=>$file)
			var_dump($file);
	}
/**
 * Only if got the URL param 'do'
 */
    
    function handleRequest($keep_submit=false){
		// assign a method (post/get)
		$method=$this->action;
		if ($_SERVER['REQUEST_METHOD']=='POST')
			$params=$_POST;
		elseif ($_SERVER['REQUEST_METHOD']=='GET')
			$params=$_GET;
		if($params){
			if(!$keep_submit)
				unset($params['submit']);
			$this->$method($params);
		}
	}

    function create($table,$data) {
        //var_dump($data);
		/*	array
  			  'name' => string 'Сержик' (length=12)
			  'contacts' => string 'на лавочке' (length=19)
			  'submit' => string 'Отправить' (length=18)
			  */
			$Db=new Db();
			$keys=$vals=array();
			foreach($data as $key=>$value){
				$keys[]=$key;
				$vals[]=$value;
			}
			$fields='`'.implode('`, `',$keys).'`';
			$values="'".implode("', '",$vals)."'";
			$query="INSERT INTO ".$table." (".$fields.", `datetime`) VALUES (".$values.", '".date('Y-m-d H:i:s')."')";
			if($rows=$Db->execute($query)){
				echo "<div>Tne number of added values: ".$rows."</div>";
				return true;
			}else{
				echo $this->no_params_mess;
				return false;
			}
    }

	/*function read($query){
		$Db=new Db();
		$Db->getConnect();
		// returns an associative array:
		if(!$result=$Db->query($query)->fetchAll(PDO::FETCH_ASSOC)){
			$this->showError($Db);	
			return false;
		}else
			return $result;
	}*/
