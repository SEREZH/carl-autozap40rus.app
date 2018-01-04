jQuery( document ).ready(function() {

	console.log('jQuery: READY');
	new WOW().init();
 	
 	jQuery("#FormDraftContactPhone").mask("+7(999)999-99-99");
});


function validateFormDraft(obj, e) {
	console.log('validateFormDraft: BEGIN');

	//Печать имён и значений свойств с помощью Array.forEach
	//Object.getOwnPropertyNames(ev).forEach(function(val, idx, array) {console.log(val + ' -> ' + ev[val]);});

	if (typeof e !== 'undefined') {
		console.log('validateFormDraft: e.type='+e.type);
		console.log('validateFormDraft: e.target='+e.target);
		console.log('validateFormDraft: e.target.form.id='+e.target.form.id);
		console.log('validateFormDraft: e.target.form.name='+e.target.form.name);
		console.log('validateFormDraft: e.target.form.method='+e.target.form.method);
		console.log('validateFormDraft: obj.form.id='+obj.form.id);
		console.log('validateFormDraft: obj.form.name='+obj.form.name);
		console.log('validateFormDraft: obj.form.method='+obj.form.method);
		console.log('validateFormDraft: obj.formAction='+obj.formAction);
		console.log('validateFormDraft: obj.formMethod='+obj.formMethod);
		console.log('validateFormDraft: obj.formTarget='+obj.formTarget);
		console.log('validateFormDraft: obj.id='+obj.id);
		console.log('validateFormDraft: obj.name='+obj.name);
		console.log('validateFormDraft: obj.type='+obj.type);
		console.log('validateFormDraft: obj.pattern='+obj.pattern);
		console.log('validateFormDraft: obj.value='+obj.value);
		console.log('validateFormDraft: obj.value.length='+obj.value.length);
		console.log('validateFormDraft: e.target.value='+e.target.value);
		console.log('validateFormDraft: e.target.value.length='+e.target.value.length);
	}	

	jQuery(obj).removeClass('ez-tooltip-js-valid-no');
	//FormDraftUserName
	//FormDraftContactPhone
	if (obj.name == "FormDraftUserName") { 	

		console.log('validateFormDraft::FormDraftUserName: obj.value='+obj.value);
		console.log('validateFormDraft::FormDraftUserName: obj.value.length='+obj.value.length);
		console.log('validateFormDraft::FormDraftUserName: e.target.value='+e.target.value);
		console.log('validateFormDraft::FormDraftUserName: e.target.value.length='+e.target.value.length);

		var v_value = obj.value;
		var v_title = '<span>Пожалуйста,<br>укажите Ваше имя! <br> Бляха, муха!!!<br>'+v_value+'</span>';
		console.log('validateFormDraft::FormDraftUserName: v_value='+v_value);
		console.log('validateFormDraft::FormDraftUserName: v_title='+v_title);

	   	if (obj.value.length < 5) {
	   		console.log('validateFormDraft: obj.name='+obj.name+': VALIDATE ERROR');
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
	   		console.log('validateFormDraft: obj.name='+obj.name+': VALIDATE ERROR');
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
		  	console.log('validateFormDraft obj.name='+obj.name+': VALIDATE OK');
		  	jQuery(e.target).tooltip('hide');
	   	}
	}  else if (obj.name == "FormDraftContactPhone") { 	
		var v_value_phone = obj.value;
		var v_title_phone = '<span>Пожалуйста,<br>укажите Ваш телефон!<br>'+v_value_phone+'</span>';
		console.log('validateFormDraft::FormDraftContactPhone: v_value='+v_value_phone);
		console.log('validateFormDraft::FormDraftContactPhone: v_title='+v_title_phone);

		var v_tph =v_value_phone;
		v_tph = !v_tph.match(/^\([0-9]{3}\)[0-9]{3}-[0-9]{2}\-[0-9]{2}/);
		console.log('validateFormDraft::FormDraftContactPhone: v_tph = '+v_tph);

		if (obj.value.length < 100) {
	   		console.log('validateFormDraft obj.name='+obj.name+': VALIDATE ERROR');
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
		  	console.log('validateFormDraft obj.name='+obj.name+': VALIDATE OK');
		  	jQuery(e.target).tooltip('hide');
	   	}
	} else {
		console.log('validateFormDraft obj.name='+obj.name+': VALIDATE ELSE OTHER OBJECT');	
	}
}


