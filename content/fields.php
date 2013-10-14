<?php
function putLoginAndPassword($section){?>
<span><? echo Login;
    if($section=='signin')
        echo ', '. strtolower(Email) . Orx . strtolower(PhoneNumber);
?></span>
    <input placeholder="<?=Login?>" type="text" name="login" value="" />
<span><?=Password?></span>
    <input placeholder="<?=Password?>" type="password" name="password" value="" />                    
<?    
}
