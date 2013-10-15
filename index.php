<?  session_start();
    require_once 'config.php';
    require_once 'content/fields.php';?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Вход/Регистрация</title>
        <link href="css/default.css" rel="stylesheet"/>
    <?  if($section==SIGNIN):?>
        <link href="css/signin.css" rel="stylesheet"/>
    <?  endif;?>
        <script type="text/javascript" src="js/js.js"></script>
    </head>
    <body>        
        <form method="post" id="form" onsubmit="return validateForm()">
            <div id="lang">
                <span class="strong">
                    <a href="<?=SITE_ROOT?>signup"><?=Signup?></a>
                </span>
                <span class="strong">
                    <a href="<?=SITE_ROOT?>signin"><?=Signin?></a>
                </span>
                <span class="right">
                    <a href="<?=SITE_ROOT?>ru">рус</a> | <a href="<?=SITE_ROOT?>en">en</a>
                </span>
            </div>
        <?  if($section=='signup'):?>
            <div class="sign" id="sign_up">
                <h5><?=Required?>:</h5>
            <?  putLoginAndPassword($section);?>    
                <span><?=Email?></span>
                    <input class="req" placeholder="<?=hintEmail?>" type="text" id="email" name="email" value="" />
                <br/>
                <h5><?=Optional?>:</h5>
                <span>
                    <span title="<?=hintName?>"><?=Name?></span>
                </span>
                    <input placeholder="<?=hintName?>" type="text" id="name" name="name" value="" />
                <span>    
                    <span title="<?=hintPhoneNumber?>"><?=PhoneNumber?></span>
                </span>
                    <input placeholder="<?=hintPhoneNumber?>" type="text" id="phone" name="phone" value="" />
                <br/>
                <input id="btnSubmit" type="submit" value="<?=JoinUs?>">    
            </div>
        <?  else:?>
            <div class="sign" id="sign_in">
                <?  putLoginAndPassword($section);?>                    
                <br/>
                <input id="btnSubmit" type="submit" value="<?=Enter?>">
            </div>
        <?  endif?>
        </form>
    </body>
</html>
