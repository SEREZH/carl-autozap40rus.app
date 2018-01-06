<?php

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