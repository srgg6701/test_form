window.onload=function(){
    /* Если пытались отправить данные формы, назначим наблюдателей невалидных полей;
     * нужно для того, чтобы убрать с них выделение. 
     * Достигается удалением класса "invalid" у поля. 
     */
    document.getElementById('btnSubmit').addEventListener('click', function(){
        // СНЯТЬ пометку невалидности с полей при получении фокуса:
        var inputs = getInputs();
        for(var i=0, j=inputs.length; i<j; i++){
            // добавить наблюдателя к полю:
            inputs[i].addEventListener('focus',function(){
                if(this.type!='submit'){
                    // удалить класс:
                    this.removeAttribute('class');
                    if(this.type=='password'){
                        if(){
                            
                        }
                    }
                }
            });            
        }
    });    
};
/**
 * Получить поля формы
 */
function getInputs() {
    return document.getElementById('form').getElementsByTagName('input');
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
        var err=0,inputs = getInputs();
        for(var i = 0, j=inputs.length; i<j; i++){
            console.log('current element.id: '+inputs[i].id);
            if(inputs[i].type!='submit'){
                if(!inputs[i].value){
                    if(inputs[i].getAttribute('class')=='req'){                
                        console.log('invalid! No required value!');
                        err++;
                        setInvalid(inputs[i]);
                    }
                }else{
                    err+=checkFiltered(inputs[i]);
                }
            }
        }
    }catch(e){
        console.log('The caught error: '+e.message);
    }
    return false;
}