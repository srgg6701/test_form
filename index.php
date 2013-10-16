<?  session_start();
    require_once 'config.php';
    require_once 'content/fields.php';
	if($post=$_POST) 
        if($post['btnSubmit'])
            require_once 'scripts/crud.php';?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?=TestFormPage?></title>
        <link href="css/default.css" rel="stylesheet"/>
    <?  if($section==SIGNIN):?>
        <link href="css/signin.css" rel="stylesheet"/>
    <?  endif;?>
		<script>
		var filters = [];
		// создать фильтры для валидации значений полей:
	<?	foreach($filters as $filter=>$pattern):
	?>
	filters['<?=$filter?>']=<?=$pattern?>;
	<?	endforeach;
	?>
	</script>
		<script type="text/javascript" src="js/js.js"></script>
    </head>
    <body> 
    <?  $file_name=(isset($post['btnEnter'])||strstr($_SERVER['REQUEST_URI'], '/account'))? 
			'account':'form'; 
		require_once 'content/'.$file_name.'.php';?>
    </body>
</html>
