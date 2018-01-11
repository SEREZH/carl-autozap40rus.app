var ez_flipclock_clock;

jQuery( document ).ready(function() {
	// Grab the current date
	var currentDate = new Date();
	// Set some date in the future. In this case, it's always Jan 1
	var ye = currentDate.getFullYear();
	var mo = currentDate.getMonth()+1;
	console.log('ez_flipclock_clock: ye='+ye);
	console.log('ez_flipclock_clock: mo='+mo);
	var futureDate  = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1);
	// Calculate the difference in seconds between the future and current date
	var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;
	ez_flipclock_clock = jQuery('.ez-flipclock-clock').FlipClock(diff, {
		clockFace: 'DailyCounter',
		countdown: true,
		language: 'ru'
	});
	console.log('jQuery - app: READY');
	new WOW().init();
 	jQuery("#formZakazContactPhone").mask("+7(999)999-99-99");
 	var ajaxSubmitOptionsFormZakaz = { 
        target:    '#formZakazResult',   // target element(s) to be updated with server response 
		//beforeSubmit:  showRequest,  // pre-submit callback 
        success:   showResponseFormZakaz,  // post-submit callback 
 		url:       'php/ez-form-zakaz.php'         // override for form's 'action' attribute 
        // Other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
    // bind to the form's submit event 
    $('#formZakaz').submit(function() { 
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 
        $(this).ajaxSubmit(ajaxSubmitOptionsFormZakaz); 
        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    }); 
});
// post-submit callback 
function showResponseFormZakaz(responseText, statusText, xhr, $form)  { 
	var status  	= statusText;
	var jsonObj		= JSON.parse(responseText);
	var errCode 	= jsonObj['err_code'];
    var errMsgM 	= jsonObj['err_msg_m'];
    var errMsgS 	= jsonObj['err_msg_s'];
    var errMsgL 	= jsonObj['err_msg_l'];
    var clientID	= jsonObj['client_id']; 
    var clientName 	= jsonObj['name']; 
    var clientPhone	= jsonObj['phone'];
    
    var carID	= jsonObj['car_id'];
    var orderID	= jsonObj['order_id'];
    var cmtApp	= jsonObj['cmt_app'];

	if (errCode == '0') {
		var errBodyText = 'err_code='+errCode+
			'<br>err_msg_m='+errMsgM+
			'<br>err_msg_s='+errMsgS+
			'<br>err_msg_l='+errMsgL+
			'<br>client_id='+clientID+
			'<br>name='		+clientName+
			'<br>phone='	+clientPhone+
			'<br>car_id='	+carID+
			'<br>order_id='	+orderID+
			'<br>cmt_app='	+cmtApp;
		$('#modalFormZakazErrorBodyText').html(errBodyText);
		$('#modalFormZakazError').modal();
	} else {
		if (errCode == '-2001'||errCode == '-2002') {   
			var errBodyText = 'Поля&nbsp;&laquo;Ваше&nbsp;имя&raquo;&nbsp;и&nbsp;&laquo;Ваш&nbsp;телефон&raquo; являются&nbsp;обязательными для&nbsp;заполнения!';
			errBodyText = errBodyText + '<br>' + errMsgS;
			$('#modalFormZakazErrorBodyText').html(errBodyText);
    		$('#modalFormZakazError').modal();
		}	
	}

/*
    alert('status: ' + statusText + 
    	'\n\nresponseText: \n' + responseText + 
    	'\n\njsonObj[err_code]: \n' + jsonObj['err_code'] + 
    	'\n\njsonObj[err_msg_m]: \n' + jsonObj['err_msg_m'] + 
    	'\n\njsonObj[err_msg_s]: \n' + jsonObj['err_msg_s'] + 
    	'\n\njsonObj[err_msg_l]: \n' + jsonObj['err_msg_l'] + 
    	'\n\njsonObj[name]: \n' + jsonObj['name'] + 
    	'\n\njsonObj[phone]: \n' + jsonObj['phone'] + 
        '\n\nThe output div should have already been updated with the responseText.'); 
*/

} 

/// -- !!! -- Похоже, что это уже не используем -- !!! ---
function sendAjaxFormZakaz(result_form, ajax_form, url) {
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

function validateFormZakaz(obj, e) {
	console.log('validateFormZakaz: BEGIN');

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

		console.log('validateFormZakaz::formZakazUserName: obj.value='+obj.value);
		console.log('validateFormZakaz::formZakazUserName: obj.value.length='+obj.value.length);
		console.log('validateFormZakaz::formZakazUserName: e.target.value='+e.target.value);
		console.log('validateFormZakaz::formZakazUserName: e.target.value.length='+e.target.value.length);

		var v_value = obj.value;
		var v_title = '<span>Пожалуйста,<br>укажите Ваше имя! <br> Бляха, муха!!!<br>'+v_value+'</span>';
		console.log('validateFormZakaz::formZakazUserName: v_value='+v_value);
		console.log('validateFormZakaz::formZakazUserName: v_title='+v_title);

	   	if (obj.value.length < 5) {
	   		console.log('validateFormZakaz: obj.name='+obj.name+': VALIDATE ERROR');
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
	   		console.log('validateFormZakaz: obj.name='+obj.name+': VALIDATE ERROR');
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
		  	console.log('validateFormZakaz obj.name='+obj.name+': VALIDATE OK');
		  	jQuery(e.target).tooltip('hide');
	   	}
	}  else if (obj.name == "formZakazContactPhone") { 	
		var v_value_phone = obj.value;
		var v_title_phone = '<span>Пожалуйста,<br>укажите Ваш телефон!<br>'+v_value_phone+'</span>';
		console.log('validateFormZakaz::formZakazContactPhone: v_value='+v_value_phone);
		console.log('validateFormZakaz::formZakazContactPhone: v_title='+v_title_phone);

		var v_tph =v_value_phone;
		v_tph = !v_tph.match(/^\([0-9]{3}\)[0-9]{3}-[0-9]{2}\-[0-9]{2}/);
		console.log('validateFormZakaz::formZakazContactPhone: v_tph = '+v_tph);

		if (obj.value.length < 100) {
	   		console.log('validateFormZakaz obj.name='+obj.name+': VALIDATE ERROR');
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
		  	console.log('validateFormZakaz obj.name='+obj.name+': VALIDATE OK');
		  	jQuery(e.target).tooltip('hide');
	   	}
	} else {
		console.log('validateFormZakaz obj.name='+obj.name+': VALIDATE ELSE OTHER OBJECT');	
	}
}


jQuery(function () {
  jQuery('[data-toggle="tooltip"]').tooltip()
});

/* circle-progress */
/*(function($) {
  $('.first.circle').circleProgress({
    value: 0.12,
    fill: {gradient: ['#ff1e41', '#ff5f43']}
  }).on('circle-animation-progress', function(event, progress, stepValue) {
    $(this).find('strong').text(Math.round(100 * (stepValue)));
  });
  $('.second.circle').circleProgress({
    	value: 0.627
  }).on('circle-animation-progress', function(event, progress, stepValue) {
    	$(this).find('strong').html(Math.round(1000 * (stepValue + 0.5)));
  });
  $('.third.circle').circleProgress({
    value: 0.75,
    fill: {gradient: [['#AD4CA6', .5], ['#C524B1', .5]], gradientAngle: Math.PI / 4}
  }).on('circle-animation-progress', function(event, progress, stepValue) {
    $(this).find('strong').html(Math.round(100 * stepValue) + '<i>%</i>');
  });
  var c4 = $('.forth.circle');
  c4.circleProgress({
    startAngle: -Math.PI / 4 * 3,
    value: 0.5,
    lineCap: 'round',
    fill: {color: '#ffa500'}
  }).on('circle-animation-progress', function(event, progress, stepValue) {
    $(this).find('strong').text(stepValue.toFixed(2).substr(1));
  });
  setTimeout(function() { c4.circleProgress('value', 0.7); }, 1000);
  setTimeout(function() { c4.circleProgress('value', 1.0); }, 1100);
  setTimeout(function() { c4.circleProgress('value', 0.5); }, 2100);
})(jQuery);
*/