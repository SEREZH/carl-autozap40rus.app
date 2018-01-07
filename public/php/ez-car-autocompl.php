<?php

$filename = 'ez-conn.php';
if (file_exists($filename)) {
    $test = array("Файл $filename существует");//echo json_encode($test, JSON_UNESCAPED_UNICODE);
} else {
	$test = array("Файл $filename не существует");//echo json_encode($test, JSON_UNESCAPED_UNICODE);
}
include $filename;
$test = array($dbName);//echo json_encode($test, JSON_UNESCAPED_UNICODE); // как бы руссификация :)

//находим id_car_mark по переданному наименованию марки car_mar.name
if (isset($_POST["car_mark"]))    { $car_mark   = $_POST["car_mark"];}      else{ $car_mark  = '';}
// инициализируем индексный массив Модели
$arModelRows = array();
//array_push($arModelRows, $car_mark);
if ($car_mark!='') {
	//формируем текст запроса для выборки Моделей для Марки
	$sqlCommandCarModel = "select id_car_model, name from car_model where id_car_mark = (select id_car_mark from car_mark where name='$car_mark' limit 1) order by name";
	//array_push($arModelRows, $sqlCommandCarModel);
	$sqlResultCarModels  = mysqli_query($connConnection, $sqlCommandCarModel) or die (mysqli_error($connConnection));		
	//array_push($arModelRows, $sqlResultCarModels);
	while ($row = mysqli_fetch_array($sqlResultCarModels, MYSQLI_NUM)) {
		array_push($arModelRows, $row[1]);
	}
}
echo json_encode($arModelRows, JSON_UNESCAPED_UNICODE); // как бы руссификация :)


/*if ($sqlResult.count()) {
	$arModelRows = array("Jaguar","Jeep");
	array_push($arModelRows, "Isdera");
} else {
	$arModelRows = array("Jaguar","Jeep","Jensen");
	array_push($arModelRows, "Koenigsegg");
}*/

//не выдал ли нам запрос ошибки 
/*if (!$sqlResultCount) {
	$err_query1 = mysqli_error($myConnection);
	printf("Errormessage: %s\n", $err_query1);
} else{ 
	$err_query1 = '0';
}*/

//$result = array("Isdera","Isuzu","IVECO","JAC","Jaguar","Jeep","Jensen","JMC","Kia","Koenigsegg");
// ответ клиенту
//echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)

//echo '"Isdera","Isuzu","IVECO","JAC","Jaguar","Jeep","Jensen","JMC","Kia","Koenigsegg","KTM","Lamborghini","Lancia"';

/*function getCarModels(car_mark)
{
  echo '"Isdera","Isuzu","IVECO","JAC","Jaguar","Jeep","Jensen","JMC","Kia","Koenigsegg","KTM","Lamborghini","Lancia"';
}*/

//if (isset($_POST["func"]))    	{ $func 	= $_POST["func"];}      else { $func  	 = '';}
//if (isset($_POST["car_mark"]))  { $car_mark = $_POST["car_mark"];}  else { $car_mark = '';}
/*$func 		= $_POST["func"];
$car_mark 	= $_POST["car_mark"];

switch ($func) {
    case 'getCarModels':
        getCarModels($car_mark);
        break;
    default: //function not found, error or something
    	echo "ERROR1";
    	//echo "ERROR2";
        break;
}*/

?>