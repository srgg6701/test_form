<?  session_start();
    require_once 'config.php';
	// файл с HTML (форма и т.п.)
    require_once 'content/fields.php';	?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?=TestFormPage?></title>
        <link href="css/default.css" rel="stylesheet"/>
    <?  if($section==SIGNIN): 
		// если форма авторизации, подключим корректирующие стили:?>
        <link href="css/signin.css" rel="stylesheet"/>
    <?  endif;
		// создать фильтры для валидации значений полей формы:?>
		<script>
		var filters = [];		
	<?	foreach($filters as $filter=>$pattern):
	?>
	filters['<?=$filter?>']=<?=$pattern?>;
	<?	endforeach;
	?>
		</script>
		<script type="text/javascript" src="js/js.js"></script>
    </head>
    <body> 
    <?  // подключение либо аккаунта юзера, либо файла с формой
		$file_name=(isset($_POST['btnEnter'])||strstr($_SERVER['REQUEST_URI'], '/account'))? 
			'account':'form'; 
		require_once 'content/'.$file_name.'.php';?>
    </body>
</html>
