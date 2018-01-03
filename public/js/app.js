jQuery( document ).ready(function() {

	console.log('jQuery: READY');

	new WOW().init();
});

function validateFormDraft() {
    var userName =  document.getElementById('FormDraftUserName').value;
    if (userName == "") {
        document.getElementById('FormDraftUserNameStatus').innerHTML = "Пожалуйста, заполните это поле!";
        return false;
    }

    var contactPhone =  document.getElementById('FormDraftContactPhone').value;
    if (contactPhone == "") {
        document.getElementById('FormDraftContactPhoneStatus').innerHTML = "Пожалуйста, заполните это поле!";
        return false;
    } 
/*
    else {
        var telformat = \+7\-[0-9]{3}\-[0-9]{3}\-[0-9]{2}\-[0-9]{2};
        if(!telformat.test(contactPhone)){
            document.getElementById('FormDraftContactPhoneStatus').innerHTML = "Неверный формат номера телефона!";
            return false;
        }
    }
    document.getElementById('FormDraftUserNameStatus').innerHTML = "Запрос отправлен оператору...";
    document.getElementById('FormDraft').submit();*/
}

