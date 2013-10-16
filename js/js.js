// образ формы. Будем размещать внутри объекты массового обращения
var userForm = new Object();

window.onload=function(){
    // создать набор используемых в проверке объектов формы:
    // все поля данных:
    if(document.getElementById('form')){
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
                // добавить наблюдателя фокуса к полю для управления стилями ячеек:
                inputs[i].addEventListener('focus',function(){
                    if(this.type!='submit'){
                        // назначить класс обязательному полю:
                        if(this.getAttribute('required'))
                            this.setAttribute('class', 'req'); 
                        else // удалить у необязательного
                            this.removeAttribute('class');
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
        // проверка вызывается при потере фокуса если оба поля для паролей 
        // существуют (т.е., мы в процессе регистрации, а НЕ авторизации):
        if(userForm.pass1&&userForm.pass2){
            userForm.pass1.addEventListener('blur', function(){
                    checkPasswordsIdentity(true);
            });
            userForm.pass2.addEventListener('blur', function(){
                    checkPasswordsIdentity(true);
            });
        }
    }
};
/**
 * Проверить совпадение паролей:
 */
function checkPasswordsIdentity(blur) {
    try{
        if( userForm.pass1.value && userForm.pass2.value    // оба поля заполнены 
            && (userForm.pass1.value!=userForm.pass2.value) // значения различаются
          ){
            // назначить стилевой атрибут неправильного заполнения
            userForm.pass1.setAttribute('class', 'req invalid');
            userForm.pass2.setAttribute('class', 'req invalid');
            // отобразить предупреждение:
            userForm.wrnBlock.style.display='block';
            return 1; // будет увеличивать счётчик ошибок
        }else{ // всё ОК
            if(blur){ 
                // скрыть сообщение о несовпадении паролей:
                userForm.wrnBlock.style.display='none'; 
                // убрать признак невалидности с обоих полей:
                userForm.pass1.setAttribute('class', 'req');
                userForm.pass2.setAttribute('class', 'req');
            }
            return 0; // не будет увеличивать счётчик ошибок
        }
    }catch(e){
        console.log(e.message+'\ncheckPasswordsIdentity()');
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
        // создадим функцию назначения полю с невалидным значением соответствующего класса:
        var setInvalid = function(element){
            var eInput = document.getElementById(element.id);
            var newClassName = (eInput.getAttribute('required'))? 'req invalid':'invalid';
            eInput.setAttribute('class',newClassName);
        };
        // создадим функцию проверки полей по шаблону:
        var checkFiltered = function(element){
            // пропустить повторный пароль, т.к. для него не назначен элемент 
            // массива фильтров:
            var field =(element.id=="password2")? 'password':element.id;
            // 2 условия, т.к. фильтры имеют противоположное направление проверки:
            if(filters[field].test(element.value)){
                if(field=='email'){
                    // 0 ошибок:
                    return 0;
                }else{
                    setInvalid(element);
                    // вернуть ошибку:
                    return 1;
                }
            }else {
                if(field=='email'){
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
            // не кнопка отправки:
            if(currentInput.getAttribute('type')!='submit'){
                if(!currentInput.value){ // поле пусто
                    if( currentInput.getAttribute('class') // класс назначен (иначе выдаст ошибку)
                        && currentInput.getAttribute('class').indexOf('req')!=-1 // обязательное поле
                      ){                
                        err++; // инкременировать счётчик ошибок
                        setInvalid(currentInput); // назначить полю стилевой атрибут ошибки
                    }
                }else{
                    if(currentInput.getAttribute('type')=='password'){
                        /*  Поскольку текущая функция вызывается только при 
                            отправки данных формы, достаточно выполнить этот
                            блок только для одного из полей паролей. 
                            Функция checkPasswordsIdentity() сверит оба.
                         */
                        // проверить совпадение паролей:
                        err+=checkPasswordsIdentity();                         
                    }
                    // в любом случае проверить валидность полей:
                    err+=checkFiltered(currentInput);
                }
            }
        }
    }catch(e){
        console.log('The caught error: '+e.message);
    }
    if(err) return false; // всё отменить из-за ошибок.
}