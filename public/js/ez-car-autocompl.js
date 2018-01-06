jQuery( document ).ready(function() {

	console.log('jQuery - ez-car-autocompl: READY');
///* 	var ajaxSubmitOptions = { 
///*        target:    '#FormDraftResult',   // target element(s) to be updated with server response 
///*        success:   showResponse,  // post-submit callback 
///* 		url:       'php/sendz.php'         // override for form's 'action' attribute 
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
///*    }; 
 
    // bind to the form's submit event 
///*    $('#FormDraft').submit(function() { 
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 
///*        $(this).ajaxSubmit(ajaxSubmitOptions); 
        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
///*        return false; 
///*    }); 
/*	jQuery('.ez-car-autocompl-form ul.mdb-autocomplete-wrap').on("show", function() { 
    	console.log("ez-car-autocompl-form - SHOW");
	});	
	*/

	/*jQuery('.ez-car-autocompl-form ul.mdb-autocomplete-wrap').on('focus', function() {
		console.log("mouse inside autocomplete");
		jQuery('.ez-car-autocompl-form ul.mdb-autocomplete-wrap').css( "background-color", "red" );
	});*/
});

var marks = [
    "AC","Acura","Alfa Romeo","Alpine","AM General","Ariel","Aro","Asia","Aston Martin","Audi","Austin",
    "Autobianchi","Baltijas Dzips","Beijing","Bentley","Bertone","Bitter","BMW","BMW Alpina","Brabus",
    "Brilliance","Bristol","Bufori","Bugatti","Buick","BYD","Byvin","Cadillac","Callaway","Carbodies",
    "Caterham","Changan","ChangFeng","Chery","Chevrolet","Chrysler","Citroen","Cizeta","Coggiola","Dacia",
    "Dadi","Daewoo","DAF","Daihatsu","Daimler","Dallas","Datsun","De Tomaso","DeLorean","Derways","Dodge",
    "DongFeng","Doninvest","Donkervoort","E-Car","Eagle","Eagle Cars","Ecomotors","FAW","Ferrari","Fiat",
    "Fisker","Ford","Foton","FSO","Fuqi","Geely","Geo","GMC","Gonow","Great Wall","Hafei","Haima","Hawtai",
    "Hindustan","Holden","Honda","HuangHai","Hummer","Hyundai","Infiniti","Innocenti","Invicta","Iran Khodro",
    "Isdera","Isuzu","IVECO","JAC","Jaguar","Jeep","Jensen","JMC","Kia","Koenigsegg","KTM","Lamborghini","Lancia",
    "Land Rover","Landwind","Lexus","Liebao Motor","Lifan","Lincoln","Lotus","LTI","Luxgen","Mahindra","Marcos",
    "Marlin","Marussia","Maruti","Maserati","Maybach","Mazda","McLaren","Mega","Mercedes-Benz","Mercury",
    "Metrocab","MG","Microcar","Minelli","Mini","Mitsubishi","Mitsuoka","Morgan","Morris","Nissan","Noble",
    "Oldsmobile","Opel","Osca","Pagani","Panoz","Paykan","Perodua","Peugeot","Piaggio","Plymouth","Pontiac",
    "Porsche","Premier","Proton","PUCH","Puma","Qoros","Qvale","Reliant","Renaissance","Renault","Renault Samsung",
    "Rolls-Royce","Ronart","Rover","Saab","Saleen","Santana","Saturn","Scion","SEAT","ShuangHuan","Skoda","Smart",
    "Soueast","Spectre","Spyker","SRT","Ssang Yong","Subaru","Suzuki","Talbot","TATA","Tatra","Tazzari","Tesla",
    "Tianma","Tianye","Tofas","Toyota","Trabant","Tramontana","Triumph","TVR","Ultima","Vauxhall","Vector",
    "Venturi","Volkswagen","Volvo","Vortex","Wartburg","Westfield","Wiesmann","Xin Kai","Zastava","Zotye","ZX",
    "Автокам","Астро","Бронто","ВАЗ","ГАЗ","Ё-мобиль","ЗАЗ","ЗИЛ","ИЖ","КамАЗ","Канонир","ЛУАЗ","Москвич","СеАЗ",
    "СМЗ","ТагАЗ","УАЗ","Эксклюзив",
];
jQuery('#ez-car-autocompl-mark').mdb_autocomplete({
    data: marks
});



// post-submit callback 
///*function showResponse(responseText, statusText, xhr, $form)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
 
    // if the ajaxSubmit method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 
 
    // if the ajaxSubmit method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 
 
///*    alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + 
///*        '\n\nThe output div should have already been updated with the responseText.'); 
///*} 


