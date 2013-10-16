<div class="common_container">
<?php
// идентифицировать юзера:
if($_POST['btnEnter']) authenticateUser($filters);
// если удалось:
if($_SESSION['user_id']){
    $user_data=getUserData();
    $user_data=$user_data[0];
    if($_GET['register']=='ok'):?>
    <h1 class="superheader"><?=Congratulations?>!</h1>
    <?
    endif;
    ?>
    <h3 class="subheader"><?=YourData?>: 
        <span class="exit"><a href="<?=SITE_ROOT?>exit"><?=GoAway?></a></span>
    </h3>
    </div>
    <div class="stuff_container">
    <?  // основные данные юзера:
        showMainUserData($arrUserFields,$user_data); ?>
        <br/>
        <hr/>
        <p><b><?=YourPix?>:</b></p>
    <?  // изображения юзера:
        showUserPix($user_data);
}else{?>
    <h4><?=YouAreUnknown?>. <a style="font-weight: normal;margin-left: 40px;" href="<?=SITE_ROOT?>signin"><?=TryAgain?></a></h4>
<?
} ?>
    </div>
    <br/>