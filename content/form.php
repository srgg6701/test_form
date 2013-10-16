<form enctype="multipart/form-data" method="post" id="form" onsubmit="return <? 
    if($section==SIGNUP):
        ?>validateForm<?
    else:?>checkCells<?
    endif;?>()">
        <div id="lang">
            <span class="strong">
                <a href="<?=SITE_ROOT?>signup"><?=Signup?></a>
            </span>
            <span class="strong">
                <a href="<?=SITE_ROOT?>signin"><?=Signin?></a>
            </span>
            <span class="right">
                <a href="<?=SITE_ROOT?>ru">рус</a> | <a href="<?=SITE_ROOT?>en">en</a>
            </span>
        </div>
<?	if(strstr($_SERVER['REQUEST_URI'],"/return"))
  		require_once 'back_to_form.php';
		
	if($section=='signup'):?>
        <div class="sign" id="sign_up">
            <h5><?=Required?>:</h5>
        <?  putLoginAndPassword($section);?>
            <div id="pass_warning" class="right"><?=PassDiff?></div>
            <br/>    
            <span><?=Email?></span>
                <input placeholder="<?=hintEmail?>" type="text" id="email" name="email" <? fillInputFromSession('email',true);?> />
            <br/>
            <h5><?=Optional?>:</h5>
            <span>
                <span title="<?=hintName?>"><?=Name?></span>
            </span>
                <input placeholder="<?=hintName?>" type="text" id="name" name="name" <? fillInputFromSession('name');?> />
            <span>    
                <span title="<?=hintPhoneNumber?>"><?=PhoneNumber?></span>
            </span>
                <input placeholder="<?=hintPhoneNumber?>" type="text" id="phone" name="phone" <? fillInputFromSession('phone');?> />
                <br/>
                <hr/>
                <p><?=UploadPic?> <?=PicExt?>:</p>
                <input style="width:100%;" name="pic" type="file">
                <hr/>
            <br/>
            <input id="btnSubmit" name="btnSubmit" type="submit" value="<?=JoinUs?>">    
        </div>
    <?  else:?>
        <div class="sign" id="sign_in">
            <?  putLoginAndPassword($section);?>                    
            <br/>
            <input id="btnEnter" name="btnEnter" type="submit" value="<?=Enter?>">
        </div>
    <?  endif?>
    </form>