<?php
/**
 * Разместить в HTML повторяющие для обеих секций (регистрация/авторизация) 
 * элементы (логин, пароль)
 */
function putLoginAndPassword($section){?>
    <? 
    $setPass=function($second_name=null){?>
    <input class="req" autocomplete="off" placeholder="<?=hintPassword?>" type="password" id="password<?=$second_name?>" name="password<?=$second_name?>" value="" />  
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
    <input class="req" autocomplete="off" placeholder="<?=hintLogin?>" type="text" id="login" name="login" value="" />
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
