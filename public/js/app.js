var ez_flipclock_clock;


function getDayDifference(i_from_date) {
    var f_todayDate = new Date();
    var f_fromDate = i_from_date;
    var f_dateDifference =  f_todayDate.getTime() - f_fromDate.getTime();
    var f_remainsDate = new Date(f_dateDifference);
    var f_remainsDays = (parseInt(f_remainsDate / (1000 * 24 * 60 * 60)));
/*    var remainsFullDays = (parseInt(remainsSec / (24 * 60 * 60)));
    var secInLastDay = remainsSec - remainsFullDays * 24 * 3600;
    var remainsFullHours = (parseInt(secInLastDay / 3600));
    var secInLastHour = secInLastDay - remainsFullHours * 3600;
    var remainsMinutes = (parseInt(secInLastHour / 60));
    var lastSec = secInLastHour - remainsMinutes * 60;
*/ 
    //alert(f_remainsMin);
    return f_remainsDays;
}    



jQuery( document ).ready(function() {
	/*console.log('jQuery - app: READY');*/
	// Grab the current date
	var currentDate = new Date();
	// Set some date in the future. In this case, it's always Jan 1
	var ye = currentDate.getFullYear();
	var mo = currentDate.getMonth()+1;
	var futureDate  = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1);
	// Calculate the difference in seconds between the future and current date
	var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;
	ez_flipclock_clock = jQuery('.ez-flipclock-clock').FlipClock(diff, {
		clockFace: 'DailyCounter',
		countdown: true,
		language: 'ru'
	});
	/*-------------------- COOKIES BEGIN --------------------*/
	var cok_cookieDateTimeCounter	= Cookies.get('cok_cookieDateTimeCounter');
	if (cok_cookieDateTimeCounter==undefined) {cok_cookieDateTimeCounter=0}
	var cok_cookieDateTimeYear 		= Cookies.get('cookieDateTimeYear');
	var cok_cookieDateTimeMonth 	= Cookies.get('cookieDateTimeMonth');
	var cok_cookieDateTimeDay 		= Cookies.get('cookieDateTimeDay');
	var cok_cookieDateTimeHour 		= Cookies.get('cookieDateTimeHour');
	var cok_cookieDateTimeMinute 	= Cookies.get('cookieDateTimeMinute');
	var cok_cookieDateTimeSec 		= Cookies.get('cookieDateTimeSec');
	if (!(	cok_cookieDateTimeDay	==undefined||
			cok_cookieDateTimeMonth	==undefined||
			cok_cookieDateTimeDay	==undefined||
			cok_cookieDateTimeHour	==undefined||
			cok_cookieDateTimeMinute==undefined||
			cok_cookieDateTimeSec	==undefined
		)) {
		var cok_cookieDateTime	= new Date(	cok_cookieDateTimeYear,	cok_cookieDateTimeMonth, cok_cookieDateTimeDay,
											cok_cookieDateTimeHour,	cok_cookieDateTimeMinute,cok_cookieDateTimeSec);	
		var cok_daysDifference = getDayDifference(cok_cookieDateTime);
	} else {
		var cok_cookieDateTime	= new Date();
		var cok_daysDifference = 1;
	};
    if (cok_daysDifference >= 1) {
    	setTimeout(function () {jQuery('#modalCookie').modal('show');}, 1500);
		setTimeout(function () {jQuery('#modalCookie').modal('hide');}, 9500);	

		cok_cookieDateTimeCounter = (cok_cookieDateTimeCounter>=100 ? 
			cok_cookieDateTimeCounter=100 : cok_cookieDateTimeCounter + 1);

		var todayDate = new Date();
		cok_cookieDateTimeYear 		= todayDate.getFullYear();
		cok_cookieDateTimeMonth 	= todayDate.getMonth();
		cok_cookieDateTimeDay 		= todayDate.getDate();
		cok_cookieDateTimeHour 		= todayDate.getHours();
		cok_cookieDateTimeMinute 	= todayDate.getMinutes();
		cok_cookieDateTimeSec 		= todayDate.getSeconds();

		Cookies.set('cookieDateTimeCounter',cok_cookieDateTimeCounter, 	{ expires: 100 });
		Cookies.set('cookieDateTimeYear',	cok_cookieDateTimeYear, 	{ expires: 100 });
		Cookies.set('cookieDateTimeMonth',	cok_cookieDateTimeMonth, 	{ expires: 100 });
		Cookies.set('cookieDateTimeDay',	cok_cookieDateTimeDay, 		{ expires: 100 });
		Cookies.set('cookieDateTimeHour',	cok_cookieDateTimeHour, 	{ expires: 100 });
		Cookies.set('cookieDateTimeMinute',	cok_cookieDateTimeMinute, 	{ expires: 100 });
		Cookies.set('cookieDateTimeSec',	cok_cookieDateTimeSec, 		{ expires: 100 });
    }
    /*-------------------- COOKIES END --------------------*/
     
	new WOW().init();
 	jQuery("#formZakazContactPhone").mask("+7(999)999-99-99");
 	jQuery("#formZakazSimpleContactPhone").mask("+7(999)999-99-99");
 	/*--- formZakaz ---*/
 	var ajaxSubmitOptionsFormZakaz = { 
        //dataType:	'json',
        //target:    	'#formZakazResult',   	// target element(s) to be updated with server response 
		//beforeSubmit:  showRequest,  		// pre-submit callback 
        success:   	showResponseFormZakaz,  // post-submit callback 
 		url:       	'php/ez-form-zakaz.php',// override for form's 'action' attribute 

        // Other available options: 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
    // bind to the form's submit event 
    jQuery('#formZakaz').submit(function() { 
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 
        jQuery(this).ajaxSubmit(ajaxSubmitOptionsFormZakaz); 
        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    }); 
    /*--- formZakazSimple ---*/
    var ajaxSubmitOptionsFormZakazSimple = { 
        success:   	showResponseFormZakaz,  // post-submit callback 
 		url:       	'php/ez-form-zakaz-simple.php',// override for form's 'action' attribute 
    };
    jQuery('#formZakazSimple').submit(function() { 
        jQuery(this).ajaxSubmit(ajaxSubmitOptionsFormZakazSimple); 
        // !!! Important !!! always return false to prevent standard browser submit and page navigation 
        return false; 
    }); 
});
// post-submit callback 
function showResponseFormZakaz(responseText, statusText, xhr, $form)  { 
    //console.log('showResponseFormZakaz: statusText='+statusText);
    //console.log('showResponseFormZakaz: responseText='+responseText);
	var ajaxStatus	= statusText;
	var jsonObj		= JSON.parse(responseText);
	var orderKey 	= jsonObj['order_key'];
	var errCode 	= jsonObj['err_code'];
    var errMsgT 	= jsonObj['err_msg_t'];
    var errMsgS 	= jsonObj['err_msg_s'];
    var errMsgL 	= jsonObj['err_msg_l'];
    var clientName 	= jsonObj['client_name']; 
    var clientPhone	= jsonObj['client_phone'];
    var carVin		= jsonObj['car_vin'];
    var carMark		= jsonObj['car_mark'];
    var carModel	= jsonObj['car_model'];
    var carGener	= jsonObj['car_gener'];
    var carPart		= jsonObj['car_part'];
    var clientID	= jsonObj['client_id']; 
    var carID		= jsonObj['car_id'];
    var orderID		= jsonObj['order_id'];
    var orderNum	= jsonObj['order_num'];
	if (errCode == '0') {
		$('#modalFormZakazSuccessTitleText').html(errMsgT);	
		$('#modalFormZakazSuccessBodyText').html(errMsgS);
		$('#modalFormZakazSuccess').modal();
		var orderDateTime = formatDate('YYYYMMDDHHmmss'); //используется - /js/formatdate.js
		Cookies.set('orderDateTime',orderDateTime, 	{ expires: 100 });
		Cookies.set('clientName',  	clientName, 	{ expires: 100 });
		Cookies.set('clientPhone',  clientPhone,	{ expires: 100 });
		Cookies.set('carVin',  		carVin, 		{ expires: 100 });
		Cookies.set('carMark',  	carMark, 		{ expires: 100 });
		Cookies.set('carModel',  	carModel, 		{ expires: 100 });
		Cookies.set('carGener',  	carGener, 		{ expires: 100 });

	} else {
		if (errCode == -2001||errCode == -2002||errCode == -2003||errCode == -2005) {   
			$('#modalFormZakazWarningTitleText').html(errMsgT);
			$('#modalFormZakazWarningBodyText').html(errMsgS);
    		$('#modalFormZakazWarning').modal();
		} else if (errCode < -9999) {
			$('#modalFormZakazDangerTitleText').html(errMsgT);
			$('#modalFormZakazDangerBodyText').html(errMsgS);
    		$('#modalFormZakazDanger').modal();
		} else {
			$('#modalFormZakazInfoTitleText').html(errMsgT);
			$('#modalFormZakazInfoBodyText').html(errMsgS);
    		$('#modalFormZakazInfo').modal();
		}
	}
} 

var cok_orderDateTime	= Cookies.get('orderDateTime');
var cok_clientName		= Cookies.get('clientName');
var cok_clientPhone		= Cookies.get('clientPhone');
var cok_carVin			= Cookies.get('carVin');
var cok_carMark			= Cookies.get('carMark');
var cok_carModel		= Cookies.get('carModel');
var cok_carGener		= Cookies.get('carGener');
jQuery("#formZakazUserName").val(cok_clientName);
jQuery("#formZakazContactPhone").val(cok_clientPhone); 
jQuery("#formZakazCarVIN").val(cok_carVin); 
jQuery("#formZakazCarMark").val(cok_carMark); 
jQuery("#formZakazCarModel").val(cok_carModel); 
jQuery("#formZakazCarGeneration").val(cok_carGener);
jQuery("#formZakazSimpleUserName").val(cok_clientName);
jQuery("#formZakazSimpleContactPhone").val(cok_clientPhone); 
jQuery("#formZakazSimpleCarVIN").val(cok_carVin); 

/// -- !!! -- Похоже, что это уже не используем -- !!! ---
/*function sendAjaxFormZakaz(result_form, ajax_form, url) {
	console.log('AJAX - sendAjaxFormZakaz BEGIN');
	console.log('AJAX - sendAjaxFormZakaz::result_form='+result_form);
	console.log('AJAX - sendAjaxFormZakaz::ajax_form='+ajax_form);
	console.log('AJAX - sendAjaxFormZakaz::url='+url);
	var v_form = jQuery("#"+ajax_form).serialize();
	console.log('AJAX - sendAjaxFormZakaz::v_form='+v_form);
    jQuery.ajax({
        url:     url, //url страницы (action_ajax_form.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: jQuery("#"+ajax_form).serialize(),  // Сериализуем объект
        success: function(response) { //Данные отправлены успешно
        	result = jQuery.parseJSON(response);
        	jQuery('#result_form').html('Имя: '+result.name+'<br>Телефон: '+result.phonenumber);
    	},
    	error: function(response) { // Данные не отправлены
            jQuery('#result_form').html('Ошибка. Данные не отправлены.');
    	}
 	}).done(function() {
          	console.log('AJAX - sendAjaxFormZakaz DONE');
	        /////setTimeout(function() {
	        /////   jQuery.fancybox.close();
	        /////}, 2000);
    });
 	console.log('AJAX - sendAjaxFormZakaz END');
}
*/
function validateFormZakaz(obj, e) {
	//console.log('validateFormZakaz: BEGIN');

	//Печать имён и значений свойств с помощью Array.forEach
	//Object.getOwnPropertyNames(ev).forEach(function(val, idx, array) {console.log(val + ' -> ' + ev[val]);});

	if (typeof e !== 'undefined') {
/*		
		console.log('validateFormZakaz: e.type='+e.type);
		console.log('validateFormZakaz: e.target='+e.target);
		console.log('validateFormZakaz: e.target.form.id='+e.target.form.id);
		console.log('validateFormZakaz: e.target.form.name='+e.target.form.name);
		console.log('validateFormZakaz: e.target.form.method='+e.target.form.method);
		console.log('validateFormZakaz: obj.form.id='+obj.form.id);
		console.log('validateFormZakaz: obj.form.name='+obj.form.name);
		console.log('validateFormZakaz: obj.form.method='+obj.form.method);
		console.log('validateFormZakaz: obj.formAction='+obj.formAction);
		console.log('validateFormZakaz: obj.formMethod='+obj.formMethod);
		console.log('validateFormZakaz: obj.formTarget='+obj.formTarget);
		console.log('validateFormZakaz: obj.id='+obj.id);
		console.log('validateFormZakaz: obj.name='+obj.name);
		console.log('validateFormZakaz: obj.type='+obj.type);
		console.log('validateFormZakaz: obj.pattern='+obj.pattern);
		console.log('validateFormZakaz: obj.value='+obj.value);
		console.log('validateFormZakaz: obj.value.length='+obj.value.length);
		console.log('validateFormZakaz: e.target.value='+e.target.value);
		console.log('validateFormZakaz: e.target.value.length='+e.target.value.length);
*/
	}	

	jQuery(obj).removeClass('ez-tooltip-js-valid-no');
	//formZakazUserName
	//formZakazContactPhone
	if (obj.name == "formZakazUserName") { 	
		/*console.log('validateFormZakaz::formZakazUserName: obj.value='+obj.value);
		console.log('validateFormZakaz::formZakazUserName: obj.value.length='+obj.value.length);
		console.log('validateFormZakaz::formZakazUserName: e.target.value='+e.target.value);
		console.log('validateFormZakaz::formZakazUserName: e.target.value.length='+e.target.value.length);*/
		var v_value = obj.value;
		var v_title = '<span>Пожалуйста,<br>укажите Ваше имя! <br>'+v_value+'</span>';
		/*console.log('validateFormZakaz::formZakazUserName: v_value='+v_value);
		console.log('validateFormZakaz::formZakazUserName: v_title='+v_title);*/
	   	if (obj.value.length < 5) {
	   		//console.log('validateFormZakaz: obj.name='+obj.name+': VALIDATE ERROR');
	   		jQuery(e.target).tooltip('dispose');
	   		jQuery(e.target).tooltip({
					trigger: 'manual',
					placement: 'top',
					fallbackPlacement: 'flip',
					offset: 0,
					html: true,
					//title: '<span>Пожалуйста,<br>укажите Ваше имя! <br> Бляха, муха!!!'+obj.value+'</span>',
					title: v_title,
			});
			jQuery(e.target).tooltip('show');
			jQuery(e.target).focus(function() {
			  jQuery(e.target).tooltip('hide');
			});
			setTimeout(function () {
				jQuery(e.target).tooltip('hide');
			}, 3500);
	   	}
	   	else if (obj.value.length > 4) {
	   		//console.log('validateFormZakaz: obj.name='+obj.name+': VALIDATE ERROR');
	   		jQuery(e.target).tooltip('dispose');
	   		jQuery(e.target).tooltip({
					trigger: 'manual',
					placement: 'top',
					fallbackPlacement: 'flip',
					offset: 0,
					html: true,
					title: v_title,
			});
			jQuery(e.target).tooltip('show');
			jQuery(e.target).focus(function() {
			  jQuery(e.target).tooltip('hide');
			});
			setTimeout(function () {
				jQuery(e.target).tooltip('hide');
			}, 3500);
	   	}
	   	else {
		  	//console.log('validateFormZakaz obj.name='+obj.name+': VALIDATE OK');
		  	jQuery(e.target).tooltip('hide');
	   	}
	}  else if (obj.name == "formZakazContactPhone") { 	
		var v_value_phone = obj.value;
		var v_title_phone = '<span>Пожалуйста,<br>укажите Ваш телефон!<br>'+v_value_phone+'</span>';
		//console.log('validateFormZakaz::formZakazContactPhone: v_value='+v_value_phone);
		//console.log('validateFormZakaz::formZakazContactPhone: v_title='+v_title_phone);

		var v_tph =v_value_phone;
		v_tph = !v_tph.match(/^\([0-9]{3}\)[0-9]{3}-[0-9]{2}\-[0-9]{2}/);
		//console.log('validateFormZakaz::formZakazContactPhone: v_tph = '+v_tph);

		if (obj.value.length < 100) {
	   		//console.log('validateFormZakaz obj.name='+obj.name+': VALIDATE ERROR');
			jQuery(e.target).tooltip('dispose');
	   		jQuery(e.target).tooltip({
					trigger: 'manual',
					placement: 'top',
					fallbackPlacement: 'flip',
					offset: 0,
					html: true,
					title: v_title_phone,
			});
			jQuery(e.target).tooltip('show');
			jQuery(e.target).focus(function() {
			  jQuery(e.target).tooltip('hide');
			});
			setTimeout(function () {
				jQuery(e.target).tooltip('hide');
			}, 3500);
	   	}
	   	else {
		  	//console.log('validateFormZakaz obj.name='+obj.name+': VALIDATE OK');
		  	jQuery(e.target).tooltip('hide');
	   	}
	} else {
		console.log('validateFormZakaz obj.name='+obj.name+': VALIDATE ELSE OTHER OBJECT');	
	}
}


jQuery(function () {
  jQuery('[data-toggle="tooltip"]').tooltip()
});


