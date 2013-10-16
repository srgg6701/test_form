        <div>
            <p class="txtRed"><?=ErrorDuringRegister?>:</p>
            <ul>
    	<?	$showInvalids=function($invalids){
				if($invalids=='xtra'):?>
                <b><?=OtherErrors?>:</b>
			<?	endif;
				foreach($_SESSION[$invalids] as $field=>$value):
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
		<?		
				endforeach;
			};
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
					//echo "<div>$invalids</div>";
					//var_dump ($_SESSION[$invalids]);
				}
        	}	
            
            ?>  
        	</ul>      
        </div>