<?php

if($_GET['register']=='ok'):?>
<h2>Поздравляем с успешной регистрацией!</h2>
<?
endif;
?>
<p>Ваши данные:</p>
<?  echo "user_id = ".$_SESSION['user_id'];

?>
