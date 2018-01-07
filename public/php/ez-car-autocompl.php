<?php

$filename = 'ez-conn.php';
if (file_exists($filename)) {
    $test = array("Файл $filename существует");
    //echo json_encode($test, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
} else {
	$test = array("Файл $filename не существует");
	//echo json_encode($test, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
}
include $filename;
$test = array($dbName);
//echo json_encode($test, JSON_UNESCAPED_UNICODE); // как бы руссификация :)

формируем текст запроса
$sqlCommand = "select id from car_model where id_car_mark=1";
//$sqlResultCount = mysqli_query($myConnection, $sqlCommand) or die (mysqli_error($myConnection));
//не выдал ли нам запрос ошибки 
/*if (!$sqlResultCount) {
	$err_query1 = mysqli_error($myConnection);
	printf("Errormessage: %s\n", $err_query1);
} else{ 
	$err_query1 = '0';
}*/

$result = array("Isdera","Isuzu","IVECO","JAC","Jaguar","Jeep","Jensen","JMC","Kia","Koenigsegg");
// ответ клиенту
echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)

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