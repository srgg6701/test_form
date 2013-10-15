<?php
/**
 * Разместить в HTML повторяющие для обеих секций (регистрация/авторизация) 
 * элементы (логин, пароль)
 */
function putLoginAndPassword($section){?>
    <? 
    $setPass=function($second_name=null) use ($section){ 
	?>
    <input class="req" autocomplete="off"<?
		if($section==SIGNUP){
			?> placeholder="<?=hintPassword?>"<? 
		}?> type="password" id="password<? 
            echo $second_name;?>" name="password<?
            echo $second_name;?>" value="<? fillInputFromSession('password');?>" />  
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
    <input class="req" autocomplete="off"<?
	if($section==SIGNUP){
		?> placeholder="<?=hintLogin?>"<? 
	}?> type="text" id="login" name="login" value="<? fillInputFromSession('login');?>" />
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
 * в случае возврата к заполнению формы из-за обнаруженных невалидных данных:
 */
function fillInputFromSession($name){
    if(isset($_SESSION['valid_data'][$name]))
        echo $_SESSION['valid_data'][$name];
    else{
        $Invalids=explode(",",Invalids);
        foreach($Invalids as $invalids)
            if($_SESSION[$invalids][$name])
                echo $_SESSION[$invalids][$name];
    }        
}