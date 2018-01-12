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

  $client_id= 0;
  $car_id   = 0;
  $order_id = 0;
  $name     = trim($name);
  $phone    = trim($phone);
  $part     = "";  
  $cmt_app  = "Комментарий приложения.";

  if ($name=='') {
      $result = array(
        'err_code'  => -2001,
        'err_msg_m' => "Ошибка при заполнении формы экспресс заказа!",
        'err_msg_s' => "Укажите, пожалуйста, Ваше имя!",
        'err_msg_l' => "Укажите, пожалуйста, Ваше имя!",
        'client_id' => $client_id,
        'car_id'    => $car_id,
        'order_id'  => $order_id,
        'name'      => $name,
        'phone'     => $phone,
        'cmt_app'   => $cmt_app,
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
        'client_id' => $client_id,
        'car_id'    => $car_id,
        'order_id'  => $order_id,
        'name'      => $name,
        'phone'     => $phone,
        'cmt_app'   => $cmt_app,
      );
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return;
  }
  // - Проверяем наличие клиента в базе по имени и телефону в таблице EZ_CLIENTS
  //формируем текст запроса
  $clientSelectQuery = "select id from ez_clients where upper(name)=upper('$name') and upper(phone)=upper('$phone') limit 1";
  $clientSelectResult = mysqli_query($connConnection, $clientSelectQuery) or die (mysqli_error($connConnection));
  if (!$clientSelectResult) { //не выдал ли нам запрос ошибки 
    $err = mysqli_error($connConnection);
    $result = array(
      'err_code'  => -2101,
      'err_msg_m' => "Ошибка при попытке проверки наличия клиента.",
      'err_msg_s' => $err,
      'err_msg_l' => $err,
      'client_id' => $client_id,
      'car_id'    => $car_id,
      'order_id'  => $order_id,
      'name'      => $name,
      'phone'     => $phone,
      'cmt_app'   => $cmt_app,
    );
    echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
    return; 
  }
  // цикл выборки, преобразованной в массив - Количество уникальных записей (имя,телефон)
  while ($rowEzClients = mysqli_fetch_array($clientSelectResult)) { 
    $client_id = $rowEzClients['id'];
  }
  //$count = count($outputEzClients); // Количество записей - 0 или 1
  // - Определяем количество выбранных запосом записей
  //$count = mysqli_num_rows($clientSelectResult); //mysqli_affected_rows() для INSERT, UPDATE, DELETE
  if ($client_id==0) {
    $client_exists = "Не определен ID клиента. Считаем, что в БД клиента нету"; //пока пишем эту хрень
  } else {
    $client_exists = "Определен ID клиента = ".$client_id.".";
  };  
  $cmt_app = $cmt_app." ".$client_exists;
  ///////////////////////////////////////////
  /////////////// ВРЕМЕННО!!! ///////////////
  $result = array(
    'err_code'  => 0,
    'err_msg_m' => "Проверка наличие клиента в базе по имени и телефону.",
    'err_msg_s' => $client_exists,
    'err_msg_l' => $client_exists,
    'client_id' => $client_id,
    'car_id'    => $car_id,
    'order_id'  => $order_id,
    'name'      => $name,
    'phone'     => $phone,
    'cmt_app'   => $cmt_app,
  );
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
  return;
  /////////////// ВРЕМЕННО!!! ///////////////
  ///////////////////////////////////////////
  if ($client_id == 0) {
    $cmt_app = $cmt_app.'<br>'.'Добавление новой записи в таблицу EZ_CLIENTS, $client_id == 0 - клиента там еще нету.';
    $clientInsertQuery = "insert into ez_clients (id,name,phone,mail) values (null, '$name','$phone', 'e-mail')";
    $sqlResultInsert = mysqli_query($connConnection, $clientInsertQuery);
    if (!$sqlResultInsert) { //не выдал ли нам запрос ошибки 
      $err = mysqli_error($connConnection);
      $cmt_app = $cmt_app.'<br>'.'Ошибка при попытке добавления клиента в БД.'.'<br>'.$err;
      $result = array(
        'err_code'  => -2201,
        'err_msg_m' => "Ошибка при попытке добавления клиента в БД.",
        'err_msg_s' => $err,
        'err_msg_l' => $err,
        'client_id' => $client_id,
        'car_id'    => $car_id,
        'order_id'  => $order_id,
        'name'      => $name,
        'phone'     => $phone,
        'cmt_app'   => $cmt_app,
      );
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    };  
    $cmt_app = $cmt_app.'<br>'.'Определяем ID добавленного клиента $clientSelectQuery определен выше.';
    $clientSelectResult = mysqli_query($connConnection, $clientSelectQuery) or die (mysqli_error($connConnection));
    if (!$clientSelectResult) { //не выдал ли нам запрос ошибки 
      $err = mysqli_error($connConnection);
      $result = array(
        'err_code'  => -2101,
        'err_msg_m' => "Ошибка при попытке проверки наличия клиента.",
        'err_msg_s' => $err,
        'err_msg_l' => $err,
        'client_id' => "0",
        'name'      => $name,
        'phone'     => $phone,
        'cmt_app'   => $cmt_app,
      );
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    }
    // цикл выборки, преобразованной в массив - Количество уникальных записей (имя,телефон)
    $outputEzClients  = array();
    $outputEzClientID = 0;
    while ($rowEzClients = mysqli_fetch_array($clientSelectResult)) { 
      $outputEzClientID = $rowEzClients['id'];
      $outputEzClients[] = $outputEzClientID;
    }



      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    }
    //$count = mysqli_affected_rows($sqlResultInsert);
    $err = "В таблицу EZ_CLIENTS додавлен клиент с ID = ".$client_id;
    $cmt_app = $cmt_app." ".$err;
    //mysqli_commit($connConnection); mysqli_close($connConnection); 
    //??? Делать ли коммит и закрывать ли сессию ???
    ///////////////////////////////////////////
    /////////////// ВРЕМЕННО!!! ///////////////
    $result = array(
      'err_code'  => 0,
      'err_msg_m' => "Добавления клиента в БД выполнено успешно.",
      'err_msg_s' => $err,
      'err_msg_l' => $err,
      'client_id' => "0",
      'name'      => $name,
      'phone'     => $phone,    
      'cmt_app'   => $cmt_app,
    );
    echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
    return;
    /////////////// ВРЕМЕННО!!! ///////////////
    ///////////////////////////////////////////
  }  

  ///////////////////////////////////////////
  /////////////// ВРЕМЕННО!!! ///////////////
  /*$err = "Клиент уже есть в БД!";
  $result = array(
    'err_code'  => 0,
    'err_msg_m' => "Проверка наличия клиента в БД.",
    'err_msg_s' => $err,
    'err_msg_l' => $err,
    'client_id' => "0",
    'name'      => $name,
    'phone'     => $phone,    
        
  );
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
  return;*/
  /////////////// ВРЕМЕННО!!! ///////////////
  ///////////////////////////////////////////
  
  // - Добавление новой записи в таблицу EZ_CARS, если там ее еще нету 
  // - нету по VIN, MARK, MODEL, GENERATION их комбинаций - в зависимости от того что ввел пользователь или вообще не ввел
  if ($vin==''||$mark==''||$model==''||$generation) {
     $carSelectQuery = "select id from ez_cars where client_id = $outputEzClientID limit 1)";
  }



  $sqlCommand = "select id from ez_clients where upper(name)=upper('$name') and upper(phone)=upper('$phone') limit 1";
  $clientSelectResult = mysqli_query($connConnection, $sqlCommand) or die (mysqli_error($connConnection));
  if (!$clientSelectResult) { //не выдал ли нам запрос ошибки 
    $err = mysqli_error($connConnection);
    $result = array(
      'err_code'  => -2101,
      'err_msg_m' => "Ошибка при попытке проверки наличия клиента.",
      'err_msg_s' => $err,
      'err_msg_l' => $err,
      'client_id' => "0",
      'name'      => $name,
      'phone'     => $phone,
      'cmt_app'   => $cmt_app,
    );
    echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
    return; 
  }
  // цикл выборки, преобразованной в массив - Количество уникальных записей (имя,телефон)
  $outputEzClients  = array();
  $outputEzClientID = 0;
  while ($rowEzClients = mysqli_fetch_array($clientSelectResult)) { 
    $outputEzClientID = $rowEzClients['id'];
    $outputEzClients[] = $outputEzClientID;
  }




  if ($count == 0) {
    $clientInsertQuery = "insert into ez_clients (id,name,phone,mail) values (null, '$name','$phone', 'e-mail')";
    $sqlResultInsert = mysqli_query($connConnection, $clientInsertQuery);
    if (!$sqlResultInsert) { //не выдал ли нам запрос ошибки 
      $err = mysqli_error($connConnection);
      $result = array(
        'err_code'  => -2201,
        'err_msg_m' => "Ошибка при попытке добавления клиента в БД.",
        'err_msg_s' => $err,
        'err_msg_l' => $err,
        'client_id' => "0",
        'name'      => $name,
        'phone'     => $phone,
        'cmt_app'   => $cmt_app,
      );
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    }
    $count = mysqli_affected_rows($sqlResultInsert);
    $err = "Количество добавленных записей в таблицу EZ_CLIENTS = ".$count;
    $cmt_app = $cmt_app." ".$err; //пока пишем эту хрень
    //mysqli_commit($connConnection); mysqli_close($connConnection); 
    //??? Делать ли коммит и закрывать ли сессию ???
    ///////////////////////////////////////////
    /////////////// ВРЕМЕННО!!! ///////////////
    $result = array(
      'err_code'  => 0,
      'err_msg_m' => "Добавления клиента в БД выполнено успешно.",
      'err_msg_s' => $err,
      'err_msg_l' => $err,
      'client_id' => "0",
      'name'      => $name,
      'phone'     => $phone,    
      'cmt_app'   => $cmt_app,
    );
    echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
    return;
    /////////////// ВРЕМЕННО!!! ///////////////
    ///////////////////////////////////////////
  }


/*
'err_msg'     => "Заказ успешно принят! Оператор свяжется с Вами в течении 15 минут! Благодарим за обращение к нам!"

  // добавление новой записи (заказа) в таблицу DRAFTS
  $myQuery = "INSERT INTO drafts (id,client,phone,vin,mark,model,generation,part,cmt_app_client) 
              VALUES (NULL, '$name','$phone', '$vin, '$mark', '$model', '$generation', '$part', '$cmt_app')";
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
