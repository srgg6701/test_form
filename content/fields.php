<?php
require_once dirname(__FILE__).'/../scripts/connect_db.php';
/**
 * Разместить в HTML повторяющие для обеих секций (регистрация/авторизация) 
 * элементы (логин, пароль)
 */
function putLoginAndPassword($section){?>
    <? 
    $setPass=function($second_name=null) use ($section){ 
	?>
    <input autocomplete="off"<?
		if($section==SIGNUP){
			?> placeholder="<?=hintPassword?>"<? 
		}?> type="password" id="password<? 
            echo $second_name;?>" name="password<?
            echo $second_name;?>" <? fillInputFromSession('password', true);?> />  
<?  };
    if($section==SIGNIN){
    ?>
    <div><? 
        echo Login . ', '. strtolower(Email) . Orx . PhoneNumber;?></div>    
<?  }else{
    ?>
    <span>
        <span title="<?=hintLogin?>"><? 
    echo Login;?></span>
    </span>
<?  }
?>
    <input autocomplete="off"<?
	if($section==SIGNUP){
		?> placeholder="<?=hintLogin?>"<? 
	}?> type="text" id="login" name="login" <? fillInputFromSession('login', true);?> />
    <?  
    if($section==SIGNIN){
        ?>
    <div>
        <?=Password?></div>
    <?  $setPass();?>
<?  }else{?>    
    <span>    
        <span title="<?=hintPassword?>"><?=Password?></span>
    </span>
    <?  $setPass();
    ?>
    <span><?=Password2?></span>
    <?  $setPass(2);
    }    
}

/**
 * Заполнить значения формы данными (включая  невалидные), размещёнными в сессии
 * в случае принудительного возврата к заполнению формы из-за обнаруженных проблем:
 */
function fillInputFromSession($name,$req=false){
    $xtraClass=null; // имя класса для дополнительной раскраски ячеек, чтоб юзер 
    //мог видеть, какие значения заняты, какие - невалидны
    echo 'value="';
    if(isset($_SESSION['valid_data'][$name]))
        echo $_SESSION['valid_data'][$name];
    else{
        $Invalids=explode(",",Invalids); // invalids,taken,xtra
        foreach($Invalids as $invalids){
            if($_SESSION[$invalids][$name]){
                echo $_SESSION[$invalids][$name];
                $xtraClass=" {$invalids}";
                break;
            }else
                $xtraClass=null;
        }
    }
    echo '"';
    if($req) 
        echo " class=\"req{$xtraClass}\"";
    elseif($xtraClass)
        echo " class=\"$xtraClass\"";
}

/**
 * Аутентифицировать юзера:
 */
function authenticateUser($filters){
    $xtra_subquery = ''; // может понадобиться подзапрос
    $login=$_POST['login'];
    $field=false;
    // сначала выяснить, что он ввёл в качестве логина - 
    // собственно логин, емэйл или тел.
    // сначала емэйл:
    if(preg_match($filters['email'], $login))
        $field='email';
    // лигин, тел.:
    elseif(!preg_match($filters['login'], $login)){
        // тел.
        if(preg_match($filters['phone'], $login)) 
            $field='login'; // если проверку на тел.номер не прошли, остался логин.
        else{ // иначе - возможен тел., т.к. шаблон для логина может его покрыть
            // удалить все пробелы из полученного "№ тел.".
            // то же самое будем делать при запросе, чтобы сравнить только те
            // символы, наличие которых обязательно
            $xtra_subquery = " OR REPLACE(phone,' ','') = '" .  
                               str_replace(" ", "", $login) . "'";
        }
    }
    if($field){ // если определили тип поля
        $Db=new Db();
        $query="SELECT id FROM users 
     WHERE ($field = '$_POST[login]'{$xtra_subquery}) 
       AND password = MD5('$_POST[password]')";
        // сохранить Id юзера в сессии:
        if(!$_SESSION['user_id']=$Db->getConnect()->query($query)->fetchColumn())
            return false;
    }
}
/**
 * Показать основные данные юзера:
 */
function showMainUserData($arrUserFields,$user_data){
    $arrUserFields=array(
        'login' =>      Login, 
        'email' =>      Email, 
        'name' =>       Name, 
        'phone' =>      PhoneNumber, 
        'datetime' =>   RegisterDate    
    );
    foreach ($user_data as $field=>$value):
        if(key_exists($field, $arrUserFields)):?>
        <div>
            <span><?=$arrUserFields[$field]?>:</span>
            <span><? 
            if($field=='datetime'&&$value)
                $value=date('d.m.Y',strtotime($value));
            echo $value? $value:'<span class="bleek">'.NotGiven.'</span>'; ?></span>
        </div>
    <?  endif;
    endforeach;
}
/**
 * Показать изображения юзера:
 */
function showUserPix(&$user_data){
    if($user_data['user_id']):
    ?><img src="content/files/<?=$user_data['user_id'] .
            substr($user_data['filename'],strripos($user_data['filename'],"."));?>" />
    <?    
    endif;
}
/**
 * Извлечь данные юзера:
 */
function getUserData(){
    $Db=new Db();
	$connect=$Db->getConnect();
    $query="SELECT * FROM users LEFT JOIN pix ON pix.user_id = users.id
                     WHERE users.id = ".$_SESSION['user_id'];
    return $connect->query($query)->fetchAll(PDO::FETCH_ASSOC);
}
