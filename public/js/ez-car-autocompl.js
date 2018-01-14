jQuery( document ).ready(function() {
	/*console.log('::ez-car-autocompl::QUERY::READY');*/
});
/*---------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------*/
// Марка, Модель, Поколение автомобиля - НАЧАЛО
/*---------------------------------------------------------------------------------------*/
/*console.log('::ez-car-autocompl::BEGIN');*/
var g_mark_val;
var g_mark_val_wet;
var g_mark_val_old;
var g_mark_val_wet_old;
var g_model_val;
var g_model_val_old;
var g_model_val_wet;
var g_model_val_wet_old;
var g_generation_val;
var g_generation_val_old;
var g_generation_val_wet;
var g_generation_val_wet_old;
/*---------------------------------------------------------------------------------------*/
// Марка автомобиля - НАЧАЛО
/*---------------------------------------------------------------------------------------*/
function ezCarAutocomplMarkFill(obj, e) {
    /*console.log('::ezCarAutocomplMarkFill');
    console.log('::ezCarAutocomplMarkFill: obj.name='+obj.name);
    console.log('::ezCarAutocomplMarkFill: obj.value='+obj.value);*/
    var v_makrs = [""];
    jQuery.ajax({
        type: 'POST',
        url: 'php/ez-car-autocompl.php', //this should be url to your PHP file
        dataType: 'json',
        data: {func: 'getCarMarks'},
        complete: function(response) {
            //console.log('::ezCarAutocomplMarkFill::AJAX::MARKS::COMPLETE: response='+
            //JSON.stringify(response));
        },
        success: function(response) {
            //console.log('::ezCarAutocomplMarkFill::AJAX::MARKS::SUCCESS: response='+response);
            //console.log('::ezCarAutocomplMarkFill::AJAX::MARKS::SUCCESS: response='+
            //JSON.stringify(response));
            v_models = response;
            jQuery('#formZakazCarMark').mdb_autocomplete({
                data: v_models
            });
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log('::ezCarAutocomplMarkFill::AJAX::MARKS::ERROR: '+xhr.status);
            console.log('::ezCarAutocomplMarkFill::AJAX::MARKS::ERROR: '+thrownError);
        }
    });
}    
function ezCarAutocomplMarkBlur(obj, e) {
    /*console.log('::ezCarAutocomplMarkBlur');   
    console.log('::ezCarAutocomplMarkBlur: obj.name='+obj.name);
    console.log('::ezCarAutocomplMarkBlur: obj.value='+obj.value);*/
    g_mark_val_wet = ""; // сброс "мокрой" марки автомобиля
    /*console.log('::ezCarAutocomplMarkBlur: 1 - g_mark_val='+g_mark_val);
    console.log('::ezCarAutocomplMarkBlur: 1 - g_mark_val_wet='+g_mark_val_wet);
    console.log('::ezCarAutocomplMarkBlur: 1 - g_mark_val_old='+g_mark_val_old);
    console.log('::ezCarAutocomplMarkBlur: 1 - g_mark_val_wet_old='+g_mark_val_wet_old);*/
    g_mark_val_wet = jQuery('#formZakazCarMark').val(); // корректировка "мокрой" марки автомобиля
    /*console.log('::ezCarAutocomplMarkBlur: 2 - g_mark_val_wet='+g_mark_val_wet);*/
    if (g_mark_val_old!=g_mark_val||
        g_mark_val_old==null||
        (g_mark_val_wet!=null&&g_mark_val_wet!=g_mark_val_old)
       ) 
    {
        g_mark_val_wet_old  = g_mark_val_wet;
        /*console.log('::ezCarAutocomplMarkBlur: IF :g_mark_val_old='+g_mark_val_old);*/
        jQuery('#formZakazCarModel').val('');    
    }
}
/*---------------------------------------------------------------------------------------*/
// Марка автомобиля - ОКОНЧАНИЕ
/*---------------------------------------------------------------------------------------*/
// Модель автомобиля - НАЧАЛО
/*---------------------------------------------------------------------------------------*/
function ezCarAutocomplModelFill(obj, e) {
    /*console.log('::ezCarAutocomplModelFill');
    console.log('::ezCarAutocomplModelFill: obj.name='+obj.name);
    console.log('::ezCarAutocomplModelFill: obj.value='+obj.value);
    console.log('::ezCarAutocomplModelFill: 1 - g_mark_val='+g_mark_val);
    console.log('::ezCarAutocomplModelFill: 1 - g_mark_val_wet='+g_mark_val_wet);
    console.log('::ezCarAutocomplModelFill: 1 - g_mark_val_old='+g_mark_val_old);*/
    g_mark_val = jQuery('#formZakazCarMark').val(); // корректировка текущей марки автомобиля
    console.log('::ezCarAutocomplModelFill: 2 - g_mark_val='+g_mark_val);
    
    var v_models = [""];
    if (g_mark_val == null||g_mark_val == undefined||g_mark_val == '') {
        /*console.log('::ezCarAutocomplModelFill: IF');*/
        jQuery('#formZakazCarModel').val('');
        jQuery('#formZakazCarModel').mdb_autocomplete({
            data: v_models
        });
    } else if (g_mark_val_old != g_mark_val) {
        g_mark_val_old = g_mark_val;
        /*console.log('::ezCarAutocomplModelFill: ELSE IF');*/
        jQuery.ajax({
            type: 'POST',
            url: 'php/ez-car-autocompl.php', //this should be url to your PHP file
            dataType: 'json',
            data: {func: 'getCarModels', car_mark: g_mark_val},
            complete: function(response) {
                /*console.log('::ezCarAutocomplModelFill:AJAX::COMPLETE: response='+JSON.stringify(response));*/
            },
            success: function(response) {
                //console.log('AJAX::SUCCESS: response='+response);
                /*console.log('::ezCarAutocomplModelFill:AJAX::SUCCESS: response='+JSON.stringify(response));*/
                v_models = response;
                jQuery('#formZakazCarModel').mdb_autocomplete({
                    data: v_models
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log('::ezCarAutocomplModelFill:AJAX::MODEL::ERROR: '+xhr.status);
                console.log('::ezCarAutocomplModelFill:AJAX::MODEL::ERROR: '+thrownError);
            }
        });
    }
}    

function ezCarAutocomplModelBlur(obj, e) {
    /*console.log('::ezCarAutocomplModelBlur');   */
    g_mark_val = jQuery('#formZakazCarMark').val();
    g_model_val_wet = ""; // сброс "мокрой" марки автомобиля
    /*console.log('::ezCarAutocomplModelBlur: obj.name='+obj.name);
    console.log('::ezCarAutocomplModelBlur: obj.type='+obj.type);
    console.log('::ezCarAutocomplModelBlur: obj.value='+obj.value);
    console.log('::ezCarAutocomplModelBlur: e.target.value='+e.target.value);
    console.log('::ezCarAutocomplModelBlur: 1 - g_mark_val='+g_mark_val);
    console.log('::ezCarAutocomplModelBlur: 1 - g_model_val='+g_model_val);
    console.log('::ezCarAutocomplModelBlur: 1 - g_model_val_wet='+g_model_val_wet);*/
    g_model_val_wet = jQuery('#formZakazCarModel').val(); // корректировка "мокрой" модели автомобиля
    /*console.log('::ezCarAutocomplModelBlur: 2 - g_model_val_wet='+g_model_val_wet);*/
    if (g_model_val != g_model_val_wet) {
        jQuery('#formZakazCarGeneration').val('');  
    }
}
/*---------------------------------------------------------------------------------------*/
// Модель автомобиля - ОКОНЧАНИЕ
/*---------------------------------------------------------------------------------------*/
// Поколение автомобиля - НАЧАЛО
/*---------------------------------------------------------------------------------------*/ 
// Количество потомков в родителе
// $('.ez-car-autocompl-form-generation ul.mdb-autocomplete-wrap').children('li').length
/*---------------------------------------------------------------------------------------*/ 
function ezCarAutocomplGenerationFill(obj, e) {
    /*console.log('::ezCarAutocomplGenerationFill');
    console.log('::ezCarAutocomplGenerationFill: obj.name='+obj.name);
    console.log('::ezCarAutocomplGenerationFill: obj.value='+obj.value);
    console.log('::ezCarAutocomplGenerationFill: 1 - g_mark_val='+g_mark_val);
    console.log('::ezCarAutocomplGenerationFill: 1 - g_mark_val_wet='+g_mark_val_wet);
    console.log('::ezCarAutocomplGenerationFill: 1 - g_mark_val_old='+g_mark_val_old);*/
    g_mark_val = jQuery('#formZakazCarMark').val(); // корректировка текущей марки автомобиля
    g_model_val = jQuery('#formZakazCarModel').val(); // корректировка текущей модели автомобиля
    /*console.log('::ezCarAutocomplGenerationFill: 2 - g_mark_val='+g_mark_val);
    console.log('::ezCarAutocomplGenerationFill: 2 - g_model_val='+g_model_val);
    console.log('::ezCarAutocomplGenerationFill: 2 - g_model_val_old='+g_model_val_old);*/
    
    var v_generations = [""];
    if (g_model_val == null||g_model_val == undefined||g_model_val == '') {
        console.log('::ezCarAutocomplGenerationFill: IF');
        jQuery('#formZakazCarGeneration').val('');
        jQuery('#formZakazCarGeneration').mdb_autocomplete({
            data: v_generations
        });
    } else if (g_model_val_old != g_model_val) {
        g_model_val_old = g_model_val;
        console.log('::ezCarAutocomplGenerationFill: ELSE IF');
        jQuery.ajax({
            type: 'POST',
            url: 'php/ez-car-autocompl.php', //this should be url to your PHP file
            dataType: 'json',
            data: {func: 'getCarGenerations', car_mark: g_mark_val, car_model: g_model_val},
            complete: function(response) {
                console.log('AJAX::COMPLETE: response='+
                JSON.stringify(response));
            },
            success: function(response) {
                //console.log('AJAX::SUCCESS: response='+response);
                console.log('AJAX::SUCCESS: response='+
                JSON.stringify(response));
                v_generations = response;
                jQuery('#formZakazCarGeneration').mdb_autocomplete({
                    data: v_generations
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log('AJAX::GENERATION::ERROR: '+xhr.status);
                console.log('AJAX::GENERATION::ERROR: '+thrownError);
            }
        });
    }
}    

function ezCarAutocomplGenerationBlur(obj, e) {
    /*console.log('::ezCarAutocomplGenerationBlur');   */
    // корректировка наименований Марки и Модели
    g_mark_val      = jQuery('#formZakazCarMark').val();
    g_model_val     = jQuery('#formZakazCarModel').val();
    g_generation_val_wet = ""; // сброс "мокрого" поколения автомобиля
    /*console.log('::ezCarAutocomplGenerationBlur: obj.name='+obj.name);
    console.log('::ezCarAutocomplGenerationBlur: obj.type='+obj.type);
    console.log('::ezCarAutocomplGenerationBlur: obj.value='+obj.value);
    console.log('::ezCarAutocomplGenerationBlur: e.target.value='+e.target.value);
    console.log('::ezCarAutocomplGenerationBlur: 1 - g_mark_val='+g_mark_val);
    console.log('::ezCarAutocomplGenerationBlur: 1 - g_model_val='+g_model_val);
    console.log('::ezCarAutocomplGenerationBlur: 1 - g_generation_val_wet='+g_generation_val_wet);*/
    g_generation_val_wet = jQuery('#formZakazCarGeneration').val(); // корректировка "мокрого" поколения автомобиля
    /*console.log('::ezCarAutocomplGenerationBlur: 2 - g_generation_val_wet='+g_generation_val_wet);*/
}
// Поколение автомобиля - ОКОНЧАНИЕ
/*---------------------------------------------------------------------------------------*/ 
/*---------------------------------------------------------------------------------------*/ 
/*console.log('::ez-car-autocompl::CAR MARK FILL');*/
var v_makrs = [""];
jQuery.ajax({
    type: 'POST',
    url: '/php/ez-car-autocompl.php',
    dataType: 'json',
    data: {func: 'getCarMarks'},
    complete: function(response) {
        //console.log('::ezCarAutocomplMarkFill::AJAX::MARKS::COMPLETE: response='+
        //    JSON.stringify(response));
    },
    success: function(response) {
        //console.log('::ezCarAutocomplMarkFill::AJAX::MARKS::SUCCESS: response='+
        //    JSON.stringify(response));
        v_marks = response;
        jQuery('#formZakazCarMark').mdb_autocomplete({
            data: v_marks
        });
    },
    error: function (xhr, ajaxOptions, thrownError) {
        console.log('::ezCarAutocomplMarkFill::AJAX::MARKS::ERROR: '+xhr.status);
        console.log('::ezCarAutocomplMarkFill::AJAX::MARKS::ERROR: '+thrownError);
    }
});