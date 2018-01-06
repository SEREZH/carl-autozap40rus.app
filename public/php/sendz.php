<?php
  //include "senda.php";//Подключаем БД
  //Подключаем БД
  $hostname = "localhost";  // название/путь сервера, с MySQL
  $username = "carl";       // имя пользователя
  $password = "carl";       // пароль пользователя
  $dbName   = "carl01";     // название базы данных
  $myConnection = mysqli_connect($hostname, $username, $password, $dbName) or die ("could not connect to mysql");
  /* check connection */ //тоже? - if (mysqli_connect_errno()) {
  if (!$myConnection) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    $err_conn = mysqli_connect_error();
    exit();
  }
  else{ 
    $err_conn = '0';
  }

  mysqli_query($myConnection, 'SET NAMES utf8') or header('Location: Error');
  //printf("Host information: %s\n", mysqli_get_host_info($myConnection));
  // заполяем переменные полей  
  if (isset($_POST["FormDraftUserName"]))    { $name   = $_POST["FormDraftUserName"];}      else{ $name  = '';}
  if (isset($_POST["FormDraftContactPhone"])){ $phone  = $_POST["FormDraftContactPhone"];}  else{ $phone = '';}
  if (isset($_POST["FormDraftCarVIN"]))      { $vin    = $_POST["FormDraftCarVIN"];}        else{ $vin   = '';}
  if (isset($_POST["FormDraftCarBrand"]))    { $brand  = $_POST["FormDraftCarBrand"];}      else{ $brand = '';}
  $cmt    = "Комментарий клиента."; //пока пишем эту хрень
  //формируем текст запроса
  $sqlCommand = "select id from drafts where client='$name' and phone='$phone'";
  $sqlResultCount = mysqli_query($myConnection, $sqlCommand) or die (mysqli_error($myConnection));
  //не выдал ли нам запрос ошибки 
  //if (!mysqli_query($myConnection, "SET a=1")) {
  if (!$sqlResultCount) {
    $err_query1 = mysqli_error($myConnection);
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
  $myQuery = "INSERT INTO drafts (id, phone, client, brand, vin,  cmt_client) 
              VALUES (NULL,'$phone', '$name','$brand', '$vin', '$cmt')";
/*  $myQuery = "INSERT INTO test VALUES (NULL, '$name', '$phone', '$count')";*/
  $sqlResultInsert = mysqli_query($myConnection, $myQuery);
  //не выдал ли нам запрос ошибки 
  if (!$sqlResultInsert) {
    $err_query2 = mysqli_error($myConnection);
    printf("Errormessage: %s\n", $err_query2);
  } else { 
    $err_query2 = '0';
  }
  /*mysqli_commit($myConnection); mysqli_close($myConnection);*/
  $result = array(
        'err_conn'    => $err_conn,
        'err_query1'  => $err_query1,
        'err_query2'  => $err_query2,
        'name'        => $name,
        'phone'       => $phone,
        'vin'         => $vin,
        'brand'       => $brand
  );
  // ответ клиенту
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
?>
