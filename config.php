<?php
if(strstr($_SERVER['REQUEST_URI'],'/exit')){
    // удалить сохранённые в сессии вводимые юзером данные
    session_destroy(); 
    session_start();
}

if(!isset($_SESSION['ROOT'])) //установить root сайта:
    $_SESSION['ROOT']='http://localhost/_temp/test/smithandpartners/';
//установить константы для сайта:
define('SITE_ROOT', $_SESSION['ROOT']); // синоним значения в сессии, для краткости
define('URI', $_SERVER['REQUEST_URI']); 
define('LANG1','ru');
define('LANG2','en');
define('SIGNIN','signin');  // раздел аутентификации
define('SIGNUP','signup');  // раздел регистрации
define('PicExt','gif,jpg,png'); // допустимые форматы графических файлов
// типы невалидных данных: 
// * недопустимые символы
// * занятые значения
// * проблемные записи (напр., - несвпадение паролей при регистрации)
define('Invalids','invalids,taken,xtra');
// используемые языки
if(preg_match('/\/'.LANG2.'\b/',URI))
    $lang=LANG2;
elseif(preg_match('/\/'.LANG1.'\b/',URI))
    $lang=LANG1;
    
if(!$lang){ // метку языка не передавали явно (в URI):
    // сохраним в переменной активный язык сайта:
    if(isset($_SESSION['LANG']))
        $lang = $_SESSION['LANG'];
    else
        $lang = $_SESSION['LANG'] = LANG1;
}else{
    $_SESSION['LANG']=$lang;
}
// сохранить в переменно текущий раздел (аутентификация/регистрация):
$section = (preg_match('/\/signin\b/',URI))?  SIGNIN: SIGNUP;
// если аутентификация, удалим (если есть) из сессии невалидные значения, 
// полученные после попытки регистрации:
if($section == SIGNIN){
    $sInvalids=explode(",", Invalids);
    foreach($sInvalids as $invalid)
        unset($_SESSION[$invalid]);
}

// Создадим рус-англ. словарь:
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
    'RegisterDate'=>'Дата регистрации>Date of registration',
    'NotGiven'=>'Не предоставлено>Not given',
    'Congratulations'=>'Поздравляем с успешной регистрацией>Thank you for joining us',
    'YourData'=>'Ваши данные>Your data',
    'YourPix'=>'Ваши изображения>Your images',
    'YouAreUnknown'=>'К сожалению, вы не идентифицированы>Sorry, but you have not been identified...',
    'GoAway'=>'Выйти>Exit',
    'Home'=>'На главную>Home',
    'TryAgain'=>'Повторить попытку>Try again',
    'TestFormPage'=>'Тестовая страница>Test Form Page',
    // конец полей
    'Orx'=>' или > or ',
    'Enter'=>'Войти>Enter',
    'JoinUs'=>'Зарегистрироваться>Sign up',
    'Required'=>'Обязательные поля>Required fields',
    'Optional'=>'Необязательные поля>Optional fields',
    // подсказки по допустимым значениям полей (см. функцию JS validateForm()):
    'hintLogin'=>'Буквы (только лат.), цифры, дефисы, подчёркивания и точки>Letters, numbers, hyphens, underscores and dots',       // /[^a-zA-Z0-9\-_\.]/
    'hintPassword'=>'Буквы (только лат.) и цифры>Letters and numbers',    // /[^a-zA-Z0-9]/
    'hintEmail'=>'Укажите свой e-mail>Input here your real e-mail',       // /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
    'hintName'=>'Буквы, цифры, дефисы и пробелы>Letters, numbers, hyphens, and spaces',        // /[^a-zA-Z0-9\-\s]/ 
    'hintPhoneNumber'=>'Цифры и пробелы (необязательно)>Numbers and spaces (optional)', // /[^0-9\s]/
    'PassDiff'=>'Пароли не совпадают>Passwords are different',
    'UploadPic'=>'Вы можете загрузить изображение в форматах>You can upload an image in formats',
    'WrongLogin'=>'Недопустимые символы в логине>Invalid login',    
    'WrongPassword'=>'Недопустимые символы в пароле>Invalid password',    
    // PassDiff
    'WrongEmail'=>'Недопустимый e-mail>Invalid e-mail',    
    'WrongName'=>'Недопустимые символы в имени пользователя>Invalid name',    
    'WrongPhoneNumber'=>'Недопустимый формат № телефона>Invalid phone number',        
    'WrongPicExt'=>'Недопустимое расширение файла изображения>Invalid image extension', 
    'WrongFieldsValues'=>'Недопустимые значения полей>Invalid fields values',
    'TakenValues'=>'Значения, занятые другими пользователями>Fields values taken by other users',
    'ErrorDuringRegister'=>'Во время регистрации возникли ошибки. Пожалуйста, исправьте их и попробуйте снова>Some errors occurred while registration. Please, fix them and try again',
    'OtherErrors'=>'Прочие ошибки>Other errors'
);
// сохранить словарь в константах для дальнейшего использования на стр.
foreach ($words_content as $mask=>$words){
    $seppos = strpos($words, ">");
    $content = ($lang==LANG1)? 
        substr($words, 0, $seppos)
        : substr($words, $seppos+1);
    define($mask,$content);
}
// создать универсальные (php/js) фильтры валидации:
$filters = array(
    'login'         =>"/[^a-zA-Z0-9\-_\.]/",
    'password'      =>"/[^a-zA-Z0-9]/",
    'email'         =>"/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/",
    'name'          =>"/[^а-пр-яА-ПР-Яa-zA-Z0-9\-\s]/",
    'phone'         =>"/[^0-9\s]/"
);?>
