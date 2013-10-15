var userForm = new Object();

window.onload=function(){
    // создать набор используемых в проверке объектов формы:
    // все поля данных:
    userForm.inputs = document.getElementById('form').getElementsByTagName('input');
    // пароли:
    userForm.pass1 = document.getElementById('password');
    userForm.pass2 = document.getElementById('password2');
    // блок с предупреждением о несовпадении паролей:
    userForm.wrnBlock = document.getElementById('pass_warning');
    // кнопка отправки данных формы (регистрация):
    userForm.button = document.getElementById('btnSubmit');
    // кнопка отправки данных формы (авторизация):
    userForm.button2 = document.getElementById('btnEnter');
    /* Если пытались ОТПРАВИТЬ данные формы, назначим наблюдателей невалидных полей;
     * нужно для того, чтобы убрать с них выделение при получении фокуса. 
     * Достигается заменой класса "invalid" у поля на "req". 
     */
    var dropInvalidity = function(){
        var inputs = userForm.inputs;          
        for(var i=0, j=inputs.length; i<j; i++){
            // добавить наблюдателя к полю:
            inputs[i].addEventListener('focus',function(){
                if(this.type!='submit'){
                    // удалить класс:
                    this.setAttribute('class', 'req');                    
                }
            });            
        }
    };
    if(userForm.button)
        userForm.button.addEventListener('click', function(){
            // СНЯТЬ пометку невалидности:
            dropInvalidity();
        });
    if(userForm.button2)
        userForm.button2.addEventListener('click', function(){
            // СНЯТЬ пометку невалидности:
            dropInvalidity();
        });
    // Дополнительно назначить наблюдателей для полей с паролями для:
    // * Отображения сообщения о несовпадении
    // * Сокрытия сообщения, если поля совпадают
    // проверка вызывается при потере фокуса
    // если оба поля для паролей существуют (т.е., мы в процессе регистрации, 
    // а НЕ авторизации):
    if(userForm.pass1&&userForm.pass2){
        userForm.pass1.addEventListener('blur', function(){
                checkPasswordsIdentity(true);
        });
        userForm.pass2.addEventListener('blur', function(){
                checkPasswordsIdentity(true);
        });
    }
};
/**
 * Проверить совпадение паролей:
 */
function checkPasswordsIdentity(blur) {
    if( userForm.pass1.value && userForm.pass2.value    // оба поля заполнены 
        && (userForm.pass1.value!=userForm.pass2.value) // значения различаются
      ){
        console.log('Пароли не совпадают!');
        userForm.pass1.setAttribute('class', 'req invalid');
        userForm.pass2.setAttribute('class', 'req invalid');
        userForm.wrnBlock.style.display='block';
        return 1;
    }else{
        if(blur){ 
            // скрыть сообщение о несовпадении паролей:
            userForm.wrnBlock.style.display='none'; 
            // убрать признак невалидности с обоих полей:
            userForm.pass1.setAttribute('class', 'req');
            userForm.pass2.setAttribute('class', 'req');
        }
        return 0;
    }
}
/**
 * Проверить, заполнены ли поля логина и пароля при авторизации:
 */
function checkCells() {
    var err=0, loginField = document.getElementById('login');
    if(!loginField.value){
        loginField.setAttribute('class', 'req invalid');
        err++;
    }
    if(!userForm.pass1.value){
        userForm.pass1.setAttribute('class', 'req invalid');
        err++;
    }
    if(err) return false;
}
/**
 * Проверить валидность значений полей формы при регистрации:
 */
function validateForm() {
    try{
        var filters = [];
        // создать фильтры для валидации значений полей:
        filters['login']=/[^a-zA-Z0-9\-_\.]/;
        filters['password']=/[^a-zA-Z0-9]/;
        filters['email']=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        filters['name']=/[^а-яёА-ЯЁa-zA-Z0-9\-\s]/;
        filters['phone']=/[^0-9\s]/;
        // создадим функцию назначения полю с невалидным значением соответствующего класса:
        var setInvalid = function(element){
            console.log('element.id = '+element.id);
            document.getElementById(element.id).setAttribute('class','req invalid');
        };
        var checkFiltered = function(element){            
            if(filters[element.id].test(element.value)){
                if(element.id=='email'){
                    //console.log('value = '+element.value+', filter = '+filters[element.id]);
                    // 0 ошибок:
                    return 0;
                }else{
                    //console.log('invalid! '+element.id+': '+element.value);
                    setInvalid(element);
                    // вернуть ошибку:
                    return 1;
                }
            }else {
                if(element.id=='email'){
                    setInvalid(element);
                    return 1;
                }else{
                    return 0;
                }
            }
        };
        // получить все поля данных и проверить в цикле:
        var currentInput, err=0, inputs = userForm.inputs;         
        for(var i = 0, j=inputs.length; i<j; i++){
            currentInput = inputs[i];
            console.log('current element.id: '+currentInput.id);
            if(currentInput.getAttribute('type')!='submit'){
                console.log('type = '+currentInput.getAttribute('type')+', value = '+currentInput.value+', req = ');
                if(!currentInput.value){
                    if(currentInput.getAttribute('class').indexOf('req')!=-1){                
                        console.log('invalid! No required value!');
                        err++;
                        setInvalid(currentInput);
                    }
                }else{
                    //console.log('value is not empty');                    
                    if( currentInput.getAttribute('type')=='password'){
                        /*  Поскольку текущая функция вызывается только при 
                            отправки данных формы, достаточно выполнить этот
                            блок только для одного из полей паролей. 
                            Функция checkPasswordsIdentity() сверит оба.
                         */
                        // проверить совпадение паролей:
                        err+=checkPasswordsIdentity();                        
                    }else
                        err+=checkFiltered(currentInput);
                }
            }
        }
    }catch(e){
        console.log('The caught error: '+e.message);
    }
    console.log('err = '+err);
    if(err) return false;
}