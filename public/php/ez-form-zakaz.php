<?php
  //Подключаем БД = include ez-conn.php
  $filename = 'ez-conn.php';
  if (file_exists($filename)) {
    $test = array("Файл $filename существует");//echo json_encode($test, JSON_UNESCAPED_UNICODE);
  } else {
    $test = array("Файл $filename не существует");//echo json_encode($test, JSON_UNESCAPED_UNICODE);
  }
  include $filename;

  mysqli_query($connConnection, 'SET NAMES utf8') or header('Location: Error');
  //printf("Host information: %s\n", mysqli_get_host_info($connConnection));
  //заполяем переменные полей формы заказа 
  if (isset($_POST["formZakazUserName"]))      { $name       = $_POST["formZakazUserName"];}     else{ $name  = '';}
  if (isset($_POST["formZakazContactPhone"]))  { $phone      = $_POST["formZakazContactPhone"];} else{ $phone = '';}
  if (isset($_POST["formZakazCarVIN"]))        { $vin        = $_POST["formZakazCarVIN"];}       else{ $vin   = '';}
  if (isset($_POST["formZakazCarMark"]))       { $mark       = $_POST["formZakazCarMark"];}      else{ $mark  = '';}
  if (isset($_POST["formZakazCarModel"]))      { $model      = $_POST["formZakazCarModel"];}     else{ $model = '';}
  if (isset($_POST["formZakazCarGeneration"])) { $generation = $_POST["formZakazCarGeneration"];}else{ $generation='';}
  $part = "";  
  $cmt  = "Комментарий клиента."; //пока пишем эту хрень
  if ($name=='') {
      $result = array(
        'err_code'  => -2001,
        'err_msg_m' => "Ошибка при заполнении формы экспресс заказа!",
        'err_msg_s' => "Укажите, пожалуйста, Ваше имя!",
        'err_msg_l' => "Укажите, пожалуйста, Ваше имя!",
        'name'      => $name,
        'phone'     => $phone,
      );
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return;
  }
  if ($phone=='') {
      $result = array(
        'err_code'  => -2002,
        'err_msg_m' => "Ошибка при заполнении формы экспресс заказа!",
        'err_msg_s' => "Укажите, пожалуйста, Ваш телефон!",
        'err_msg_l' => "Укажите, пожалуйста, Ваш телефон!",
        'name'      => $name,
        'phone'     => $phone,
      );
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return;
  }
/*
  //формируем текст запроса
  $sqlCommand = "select id from drafts where client='$name' and phone='$phone'";
  $sqlResultCount = mysqli_query($connConnection, $sqlCommand) or die (mysqli_error($connConnection));
  //не выдал ли нам запрос ошибки 
  //if (!mysqli_query($connConnection, "SET a=1")) {
  if (!$sqlResultCount) {
    $err_query1 = mysqli_error($connConnection);
    printf("Errormessage: %s\n", $err_query1);
  } else{ 
    $err_query1 = '0';
  }
  // цикл выборки, преобразованной в массив
  $output = array();
  while ($row = mysqli_fetch_array($sqlResultCount)) { $output[] = $row['id'];}
  $count = count($output); 
  print_r('Количество записей = '.$count.' - '); 
  print_r($output);
  $cmt = $cmt." Количество уникальных записей (имя,телефон) = ".$count; //пока пишем эту хрень
  // добавление новой записи (заказа) в таблицу DRAFTS
  $myQuery = "INSERT INTO drafts (id,client,phone,vin,mark,model,generation,part,cmt_client) 
              VALUES (NULL, '$name','$phone', '$vin, '$mark', '$model', '$generation', '$part', '$cmt')";
  //$myQuery = "INSERT INTO test VALUES (NULL, '$name', '$phone', '$count')";
  $sqlResultInsert = mysqli_query($connConnection, $myQuery);
  //не выдал ли нам запрос ошибки 
  if (!$sqlResultInsert) {
    $err_query2 = mysqli_error($connConnection);
    printf("Errormessage: %s\n", $err_query2);
  } else { 
    $err_query2 = '0';
  }
  //mysqli_commit($connConnection); mysqli_close($connConnection);
  $result = array(
        'name'        => $name
        'err_code'    => 0,
        'err_msg'     => "Заказ успешно принят! Оператор свяжется с Вами в течении 15 минут! Благодарим за обращение к нам!"
        
  );
  // ответ клиенту
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
*/
?>
