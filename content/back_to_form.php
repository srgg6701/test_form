        <div>
            <p class="txtRed"><?=ErrorDuringRegister?>:</p>
            <ul>
    	<?	$showInvalids=function($invalids){ // выбирается с родительской страницы
				if($invalids=='xtra'):?>
                <b><?=OtherErrors?>:</b>
			<?	endif;
                // перебрать сохранённые невалидные значиения
                // (см. файл scripts/actions.php, сохранение новой записи)
				foreach($_SESSION[$invalids] as $field=>$value):
                    if($field!="password2"):
            ?>
					<li><?          
                        switch($field){
                            case 'login':
                                echo WrongLogin;
                            break;
                            case 'password':
                                echo WrongPassword;
                            break;
                            case 'pass_diff':
                                echo PassDiff;
                            break;
                            case 'email':
                                echo WrongEmail;
                            break;
                            case 'name':
                                echo WrongName;
                            break;
                            case 'phone':
                                echo WrongPhoneNumber;
                            break;
                            case 'wrong_pic':
                                echo WrongPicExt;
                            break;

                        }?></li>
		<?          endif;
				endforeach;
			};
			// Сгенерировать HTML с сообщением об ошибках:
            $Invalids=explode(",",Invalids); // invalids,taken,xtra
        	foreach($Invalids as $invalids){
				if($_SESSION[$invalids]){
					switch($invalids){
						case 'invalids':?>
						<b><?=WrongFieldsValues?>:</b>	
						<?	$showInvalids($invalids);							
						break;
						case 'taken':?>
						<b><?=TakenValues?>:</b>	
						<?	$showInvalids($invalids);
						break;
						case 'xtra':?>
						<?	$showInvalids($invalids);							
						break;
					}
				}
        	}?>  
        	</ul>      
        </div>