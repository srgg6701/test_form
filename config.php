<?php
if(!isset($_SESSION['ROOT']))
    $_SESSION['ROOT']='http://localhost/_temp/test/smithandpartners/';
define('SITE_ROOT', $_SESSION['ROOT']);
echo "<hr>SITE_ROOT = " . SITE_ROOT . "<hr>"; 

$lang = (preg_match('/\/en\b/',$_SERVER['REQUEST_URI']))? 'en' : 'ru';        

$section = (preg_match('/\/signin\b/',$_SERVER['REQUEST_URI']))? 'signin' : 'signup';

$words_content = array(
    'Signup'=>'Регистрация>Sign Up',
    'Signin'=>'Вход>Sign In',
    'Login'=>'Логин>Login',
    'Password'=>'Пароль>Password',
    ''=>'>',
    ''=>'>',
    ''=>'>',
    ''=>'>',
    ''=>'>',
    ''=>'>',
    ''=>'>',
    ''=>'>',
    ''=>'>',
    ''=>'>',
    ''=>'>',
    ''=>'>',
    
);

foreach ($words_content as $mask=>$words){
    $seppos = strpos($words, ">");
    $content = ($lang=='ru')? 
        substr($words, 0, $seppos)
        : substr($words, $seppos+1);
    define($mask,$content);
}

?>
