window.onload=function(){
    // проверить валидность данных формы:
    document.getElementById('form').addEventListener('submit', 
        function(){
            console.log("Submitted!");
            return false;
        });
};