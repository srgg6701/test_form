<?php
if(!isset($_SESSION['ROOT']))
    $_SESSION['ROOT']='http://localhost/_temp/test/smithandpartners/';
define('SITE_ROOT', $_SESSION['ROOT']);
define('URI', $_SERVER['REQUEST_URI']);
define('LANG1','ru');
define('LANG2','en');
define('SIGNIN','signin');
define('SIGNUP','signup');

if(preg_match('/\/'.LANG2.'\b/',URI))
    $lang=LANG2;
elseif(preg_match('/\/'.LANG1.'\b/',URI))
    $lang=LANG1;
    
if(!$lang){
    if(isset($_SESSION['LANG']))
        $lang = $_SESSION['LANG'];
    else
        $lang = $_SESSION['LANG'] = LANG1;
}else{
    $_SESSION['LANG']=$lang;
}

$section = (preg_match('/\/signin\b/',URI))?  SIGNIN: SIGNUP;

$words_content = array(
    'Signup'=>'Регистрация>Sign Up',
    'Signin'=>'Вход>Sign In',
    // поля:
    'Login'=>'Логин>Login',
    'Password'=>'Пароль>Password',
    'Password2'=>'Пароль ещё раз>Confirm password',
    'Email'=>'E-mail>E-mail',
    'Name'=>'Ваше имя>Your name',
    'PhoneNumber'=>'№ телефона># phone',
    // конец полей
    'Orx'=>' или > or ',
    'Enter'=>'Войти>Enter',
    'JoinUs'=>'Зарегистрироваться>Sign up',
    'Required'=>'Обязательные поля>Required fields',
    'Optional'=>'Необязательные поля>Optional fields',
    // подсказки по допустимым значениям полей (см. функцию JS validateForm()):
    'hintLogin'=>'Буквы, цифры, дефисы, подчёркивания и точки>Letters, numbers, hyphens, underscores and dots',       // /[^a-zA-Z0-9\-_\.]/
    'hintPassword'=>'Буквы и цифры>Letters and numbers',    // /[^a-zA-Z0-9]/
    'hintEmail'=>'Укажите свой e-mail>Input here your real e-mail',       // /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
    'hintName'=>'Буквы, цифры, дефисы и пробелы>Letters, numbers, hyphens, and spaces',        // /[^a-zA-Z0-9\-\s]/ 
    'hintPhoneNumber'=>'Цифры и пробелы (необязательно)>Numbers and spaces (optional)', // /[^0-9\s]/
    'PassDiff'=>'Пароли не совпадают>Passwords are different',
    ''=>'>',
    ''=>'>',
    
);

foreach ($words_content as $mask=>$words){
    $seppos = strpos($words, ">");
    $content = ($lang==LANG1)? 
        substr($words, 0, $seppos)
        : substr($words, $seppos+1);
    define($mask,$content);
}

?>
