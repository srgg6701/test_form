<?php
if(!isset($_SESSION['ROOT']))
    $_SESSION['ROOT']='http://localhost/_temp/test/smithandpartners/';
define('SITE_ROOT', $_SESSION['ROOT']);

define('LANG1','ru');
define('LANG2','en');

if(preg_match('/\/'.LANG2.'\b/',$_SERVER['REQUEST_URI']))
    $lang=LANG2;
elseif(preg_match('/\/'.LANG1.'\b/',$_SERVER['REQUEST_URI']))
    $lang=LANG1;
    
if(!$lang){
    $lang = (isset($_SESSION['LANG']))? 
        $_SESSION['LANG']:LANG1;
}
    
$section = (preg_match('/\/signin\b/',$_SERVER['REQUEST_URI']))? 'signin' : 'signup';

$words_content = array(
    'Signup'=>'Регистрация>Sign Up',
    'Signin'=>'Вход>Sign In',
    'Login'=>'Логин>Login',
    'Password'=>'Пароль>Password',
    'Email'=>'E-mail>E-mail',
    'Name'=>'Ваше имя>Your name',
    'PhoneNumber'=>'Тел. номер>Phone number',
    'Orx'=>' или > or ',
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
