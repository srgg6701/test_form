window.onload=function(){
    // создать набор используемых в проверке объектов формы:
    var userForm = {
        button     : document.getElementById('btnSubmit'),
        inputs     : document.getElementById('form').getElementsByTagName('input'),
        pass1      : document.getElementById('password'),
        pass2      : document.getElementById('password2'),
        wrnBlock   : document.getElementById('pass_warning')
    }
    /* Если пытались отправить данные формы, назначим наблюдателей невалидных полей;
     * нужно для того, чтобы убрать с них выделение. 
     * Достигается удалением класса "invalid" у поля. 
     */
    userForm.button.addEventListener('click', function(){
        // СНЯТЬ пометку невалидности с полей при получении фокуса:
        var inputs = userForm.inputs;          
        for(var i=0, j=inputs.length; i<j; i++){
            // добавить наблюдателя к полю:
            inputs[i].addEventListener('focus',function(){
                if(this.type!='submit'){
                    // удалить класс:
                    this.removeAttribute('class');                    
                }
            });            
        }
    });
    userForm.pass1.addEventListener('blur', function(){
            checkPasswordsIdentity(true);
    });
    userForm.pass2.addEventListener('blur', function(){
            checkPasswordsIdentity(true);
    });
};
/**
 * @blur - событие blur
 * Проверить совпадение паролей:
 */
function checkPasswordsIdentity(blur) {
    //
    if(userForm.pass1&&userForm.pass2){
        if( userForm.pass1.value && userForm.pass2.value 
            && (userForm.pass1.value!=userForm.pass2.value)
          ){
            console.log('Пароли не совпадают!');
            userForm.pass1.setAttribute('class', 'invalid');
            userForm.pass2.setAttribute('class', 'invalid');
            userForm.wrnBlock.style.display='block';
            return 1;
        }else{
            if(blur) 
                userForm.wrnBlock.style.display='none';
            return 0;
        }
    }  
}
/**
 * Проверить валидность значений полей формы
 */
function validateForm() {
    try{
        var filters = [];
        // создать фильтры для валидации значений полей:
        filters['login']=/[^a-zA-Z0-9\-_\.]/;
        filters['password']=/[^a-zA-Z0-9]/;
        filters['email']=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        filters['name']=/[^a-zA-Z0-9\-\s]/;
        filters['phone']=/[^0-9\s]/;
        // создадим функцию назначения полю с невалидным значением соответствующего класса:
        var setInvalid = function(element){
            console.log('element.id = '+element.id);
            document.getElementById(element.id).setAttribute('class','invalid');
        };
        var checkFiltered = function(element){
            if(filters[element.id].test(element.value)){
                if(element.id=='email'){
                    // 0 ошибок:
                    return 0;
                }else{
                    //console.log('invalid! '+element.id+': '+element.value);
                    setInvalid(element);
                    // вернуть ошибку:
                    return 1;
                }
            }
        };
        // получить все поля данных и проверить в цикле:
        var currentInput, err=0, inputs = userForm.inputs;         
        for(var i = 0, j=inputs.length; i<j; i++){
            currentInput = inputs[i];
            console.log('current element.id: '+currentInput.id);
            if(currentInput.type!='submit'){
                if(!currentInput.value){
                    if(currentInput.getAttribute('class')=='req'){                
                        console.log('invalid! No required value!');
                        err++;
                        setInvalid(currentInput);
                    }
                    if( currentInput.type=='password'){
                        err+=passIdentityChecker();                        
                    }
                }else{
                    err+=checkFiltered(currentInput);
                }
            }
        }
    }catch(e){
        console.log('The caught error: '+e.message);
    }
    return false;
}