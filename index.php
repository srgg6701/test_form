<?  session_start();
    require_once 'config.php';
    require_once 'content/fields.php';?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Вход/Регистрация</title>
        <link href="css/styles.css" rel="stylesheet"/>
        <script type="text/javascript" src="js/js.js"></script>
    </head>
    <body>        
        <form method="post" id="form">
            <div id="lang">
                <span class="strong">
                    <a href="<?=SITE_ROOT?>signup"><?=Signup?></a>
                </span>
                <span class="strong">
                    <a href="<?=SITE_ROOT?>signin"><?=Signin?></a>
                </span>
                <span class="right">
                    <a href="<?=SITE_ROOT?>">рус</a> | <a href="<?=SITE_ROOT?>en">en</a>
                </span>
            </div>
        <?  if($section=='signup'):?>
            <div class="sign" id="sign_up">
                <h5>Обязательные поля:</h5>
            <?  putLoginAndPassword($section);?>    
                <span><?=Email?></span>
                    <input placeholder="<?=Email?>" type="text" name="email" value="" />
                <br><h5>Необязательные поля:</h5>
            </div>
        <?  else:?>
            <div class="sign" id="sign_in">
                <?  putLoginAndPassword($section);?>                    
            </div>
        <?  endif?>
        </form>
    </body>
</html>
