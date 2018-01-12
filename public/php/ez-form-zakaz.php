<?php
  //Подключаем БД = include ez-conn.php
  $filename = 'ez-conn.php';
  if (file_exists($filename)) {
    $test = array("Файл $filename существует");//echo json_encode($test, JSON_UNESCAPED_UNICODE);
  } else {
    $test = array("Файл $filename не существует");//echo json_encode($test, JSON_UNESCAPED_UNICODE);
  }
  include $filename;

  function generateRandomString($length = 30) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  function setResultArray($log_key, $i_err_code, $i_err_msg_t, $i_err_msg_s, $i_err_msg_l, 
                          $i_client_name, $i_client_phone, $car_vin, $car_mark, $car_model, $car_gener, $car_part, 
                          $i_client_id, $i_car_id, $i_order_id, $i_cmt_app_html)
  {
      $f_result = array(
              'log_key'     => $i_err_code,
              'err_code'    => $i_err_code,
              'err_msg_t'   => $i_err_msg_t,
              'err_msg_s'   => $i_err_msg_s,
              'err_msg_l'   => $i_err_msg_l,
              'client_name' => $i_client_name,
              'client_phone'=> $i_client_phone,
              'car_vin'     => $car_vin,
              'car_mark'    => $car_mark,
              'car_model'   => $car_model,
              'car_gener'   => $car_gener,
              'car_part'    => $car_part,
              'client_id'   => $i_client_id,
              'car_id'      => $i_car_id,
              'order_id'    => $i_order_id,
              'cmt_app_html'=> $i_cmt_app_html,
            );
      return $f_result;
  };

  $logKey       = "Ключ журналирования";
  $logKeyID     = 0;
  $logAct       = 'Выполняемое действие';
  $errCode      = 0;
  $errMsgT      = "Заголовок модального окна";
  $errMsgS      = "Текст модального окна (короткий)";
  $errMsgL      = "Текст модального окна (длинный)";
  $clientName   = "Имя клиента";
  $clientPhone  = "Номер телефона клиента";
  $carVin       = "VIN номер автомобиля";
  $carMark      = "Марка автомобиля";
  $carModel     = "Модель автомобиля";
  $carGener     = "Поколение автомобиля";
  $carPart      = "Запчасть автомобиля (прикреплена к заказу)";  
  $clientID     = 0;
  $carID        = 0;
  $orderID      = 0;
  $cmtAppHTML   = "Комментарий приложения. Инициализация переменных файла.";

  $logKey       = generateRandomString();
  $cmtAppHTML   = $cmtAppHTML."<br>Приложение подключено к БД ".$dbName.".";
  $cmtAppHTML   = $cmtAppHTML."<br>Ключ журналирования: ".$logKey.".";
  mysqli_query($connConnection, 'SET NAMES utf8') or header('Location: Error');
  //printf("Host information: %s\n", mysqli_get_host_info($connConnection));
  //заполяем переменные полей формы заказа 
  if (isset($_POST["formZakazUserName"]))      { $clientName = $_POST["formZakazUserName"];}     else{ $clientName  = '';}
  if (isset($_POST["formZakazContactPhone"]))  { $clientPhone= $_POST["formZakazContactPhone"];} else{ $clientPhone = '';}
  if (isset($_POST["formZakazCarVIN"]))        { $carVin     = $_POST["formZakazCarVIN"];}       else{ $carVin      = '';}
  if (isset($_POST["formZakazCarMark"]))       { $carMark    = $_POST["formZakazCarMark"];}      else{ $carMark     = '';}
  if (isset($_POST["formZakazCarModel"]))      { $carModel   = $_POST["formZakazCarModel"];}     else{ $carModel    = '';}
  if (isset($_POST["formZakazCarGeneration"])) { $generation = $_POST["formZakazCarGeneration"];}else{ $generation  = '';}
  // формируем комментарий приложения
  $cmtAppHTML   = $cmtAppHTML."<br>Значения переданные из формы заказа:";
  $cmtAppHTML   = $cmtAppHTML."<br>Имя клиента: ".$clientName;
  $cmtAppHTML   = $cmtAppHTML."<br>Номер телефона клиента: ".$clientPhone;
  $cmtAppHTML   = $cmtAppHTML."<br>VIN номер автомобиля: ".$carVin;
  $cmtAppHTML   = $cmtAppHTML."<br>Марка автомобиля: ".$carMark;
  $cmtAppHTML   = $cmtAppHTML."<br>Модель автомобиля: ".$carModel;
  /// !!! ??? Можно убрать ??? !!! \\\
  $logAct         = 'Получены данные из формы заказа.';
  $logKeyID       = ++$logKeyID;
  $logInsertQuery = "insert into ez_logs (id,log_key,log_key_id,log_act,log_cmt) ".
                    "values (null, '$logKey','$logKeyID', '$logAct', '$cmtAppHTML')";
  $cmtAppHTML     = $cmtAppHTML.'<br>'.'Добавление новой записи в таблицу LOGS.'.'<br>'.$logAct;
  $cmtAppHTML     = $cmtAppHTML.'<br>'.'Текст запроса на добавление новой записи в таблицу LOGS.'.'<br>'.$logInsertQuery;                   
  $logInsertQueryResult = mysqli_query($connConnection, $logInsertQuery);
  if (!$logInsertQueryResult) { //не выдал ли нам запрос ошибки 
    $sqlErr     = mysqli_error($connConnection);
    $errCode    = -2201;
    $errMsgT    = "Ошибка при заполнении журнала приложения";
    $errMsgS    = "Ошибка при попытке добавления записи в журнал приложения:<br>".$sqlErr;
    $errMsgL    = $errMsgS;
    $cmtAppHTML = $cmtAppHTML.'<br>'.$errMsgS;
    $result = setResultArray( $logKey, 
                              $errCode, $errMsgT, $errMsgS, $errMsgL,
                              $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                              $clientID, $carID, $orderID, $cmtAppHTML);
    echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
    return; 
  };
  /// !!! ??? Можно убрать ??? !!! ///
  
  /// !!! !!! Убрать 100% !!! !!! \\\
  $logAct     = 'Успешно добавлена новая запись в таблицу LOGS.';
  $errCode    = 0;
  $errMsgT    = "Добавление новой записи в таблицу LOGS";
  $errMsgS    = "Добавление новой записи в таблицу LOGS.<br> Выполнено действие:<br>".$logAct;
  $errMsgL    = $errMsgS;
  $cmtAppHTML = $cmtAppHTML.'<br>'.$errMsgS;
  $result     = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $cmtAppHTML);
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
  return; 
  /// !!! !!! Убрать 100% !!! !!! ///


  /// !!! !!! Убрать 100% !!! !!! \\\
  
  $logAct     = 'Получены данные из формы заказа.';
  $cmtAppHTML = $cmtAppHTML.'<br>'.'Добавление новой записи в таблицу LOGS.'.'<br>'.$logAct;
  $errCode    = 0;
  $errMsgT    = "Заполнение журнала приложения";
  $errMsgS    = "В журнал приложения успешно добавлена запись:<br>".$logAct;
  $errMsgL    = $errMsgS;
  $cmtAppHTML = $cmtAppHTML.'<br>'.$errMsgS;
  $result     = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $cmtAppHTML);
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
  return; 
  /// !!! !!! Убрать 100% !!! !!! ///
  /*----------------------------------------------------------------------------------------------------------------*/
  if ($clientName=='') {
      $errCode  = -2001;
      $errMsgT  = "Ошибка при заполнении формы заказа";
      $errMsgS  = "Поля&nbsp;&laquo;Ваше&nbsp;имя&raquo;&nbsp;и&nbsp;&laquo;Ваш&nbsp;телефон&raquo; являются&nbsp;обязательными для&nbsp;заполнения!";
      $errMsgS  = $errMsgS."<br>"."Укажите, пожалуйста, Ваше имя!";
      $errMsgL  = $errMsgS;
      $result = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $cmtAppHTML);
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return;
  }
  if ($clientPhone=='') {
      $errCode  = -2002;
      $errMsgT  = "Ошибка при заполнении формы заказа";
      $errMsgS  = "Поля&nbsp;&laquo;Ваше&nbsp;имя&raquo;&nbsp;и&nbsp;&laquo;Ваш&nbsp;телефон&raquo; являются&nbsp;обязательными для&nbsp;заполнения!";
      $errMsgS  = $errMsgS."<br>"."Укажите, пожалуйста, Ваш телефон!";
      $errMsgL  = $errMsgS;
      $result = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $cmtAppHTML);
      echo json_encode($result, JSON_UNESCAPED_UNICODE);
      return;
  }
  //формируем текст запроса
  $clientSelectQuery = "select id from ez_clients where upper(name)=upper('$clientName') and upper(phone)=upper('$clientPhone') limit 1";
  $cmtAppHTML = $cmtAppHTML."<br>Проверяем наличие клиента в базе по имени и телефону в таблице EZ_CLIENTS.";
  $cmtAppHTML = $cmtAppHTML."<br>Текст запроса: ".$clientSelectQuery;
  $clientSelectResult = mysqli_query($connConnection, $clientSelectQuery) or die (mysqli_error($connConnection));
  if (!$clientSelectResult) { //не выдал ли нам запрос ошибки 
    $errCode  = -2101;
    $errMsgT  = "Ошибка проверки наличия клиента в БД!";
    $errMsgS  = mysqli_error($connConnection);
    $errMsgL  = $errMsgS;
    $result   = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $cmtAppHTML);
    echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
    return; 
  }
  $cmtAppHTML = $cmtAppHTML."<br>Определяем ID клиента. Ищем в выборке.";
  while ($rowEzClients = mysqli_fetch_array($clientSelectResult)) {$clientID = $rowEzClients['id'];}
  if ($clientID==0) {
    $client_exists = "Не определен ID клиента. Считаем, что в БД клиента нету";
  } else {
    $client_exists = "Определен ID клиента = ".$clientID.".";
  };  
  $cmtAppHTML = $cmtAppHTML."<br>".$client_exists;
  ///////////////////////////////////////////
  /////////////// ВРЕМЕННО!!! ///////////////
  $errCode = 0; 
  $errMsgT = "Проверка наличия клиента"; 
  $errMsgS = "Проверка наличия клиента в БД по имени и телефону."."<br>".$client_exists; 
  $errMsgL = $errMsgS; 
  $result = setResultArray( $logKey, 
                            $errCode, $errMsgT, $errMsgS, $errMsgL,
                            $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                            $clientID, $carID, $orderID, $cmtAppHTML);
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
  return;
  /////////////// ВРЕМЕННО!!! ///////////////
  ///////////////////////////////////////////
  if ($clientID == 0) {
    $cmtAppHTML = $cmtAppHTML.'<br>'.'Добавление новой записи в таблицу EZ_CLIENTS, $clientID == 0 - клиента там еще нету.';
    $clientInsertQuery = "insert into ez_clients (id,name,phone,mail) values (null, '$clientName','$clientPhone', 'e-mail')";
    $sqlResultInsert = mysqli_query($connConnection, $clientInsertQuery);
    if (!$sqlResultInsert) { //не выдал ли нам запрос ошибки 
      $err = mysqli_error($connConnection);
      $cmtAppHTML = $cmtAppHTML.'<br>'.'Ошибка при попытке добавления клиента в БД.'.'<br>'.$err;
      $result = array(
        'err_code'  => -2201,
        'err_msg_t' => "Ошибка при попытке добавления клиента в БД.",
        'err_msg_s' => $err,
        'err_msg_l' => $err,
        'client_id' => $clientID,
        'car_id'    => $carID,
        'order_id'  => $orderID,
        'name'      => $clientName,
        'phone'     => $clientPhone,
        'cmt_app'   => $cmtAppHTML,
      );
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    };  
    $cmtAppHTML = $cmtAppHTML.'<br>'.'Определяем ID добавленного клиента $clientSelectQuery определен выше.';
    $clientSelectResult = mysqli_query($connConnection, $clientSelectQuery) or die (mysqli_error($connConnection));
    if (!$clientSelectResult) { //не выдал ли нам запрос ошибки 
      $err = mysqli_error($connConnection);
      $result = array(
        'err_code'  => -2101,
        'err_msg_t' => "Ошибка при попытке проверки наличия клиента.",
        'err_msg_s' => $err,
        'err_msg_l' => $err,
        'client_id' => "0",
        'name'      => $clientName,
        'phone'     => $clientPhone,
        'cmt_app'   => $cmtAppHTML,
      );
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    }
    // цикл выборки, преобразованной в массив - Количество уникальных записей (имя,телефон)
    $outputEzClientID = 0;
    while ($rowEzClients = mysqli_fetch_array($clientSelectResult)) { 
      $outputEzClientID = $rowEzClients['id'];
    }



    echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
    return; 
    //$count = mysqli_affected_rows($sqlResultInsert);
    $err = "В таблицу EZ_CLIENTS додавлен клиент с ID = ".$clientID;
    $cmtAppHTML = $cmtAppHTML."<br>".$err;
    //mysqli_commit($connConnection); mysqli_close($connConnection); 
    //??? Делать ли коммит и закрывать ли сессию ???
    ///////////////////////////////////////////
    /////////////// ВРЕМЕННО!!! ///////////////
    $result = array(
      'err_code'  => 0,
      'err_msg_t' => "Добавления клиента в БД выполнено успешно.",
      'err_msg_s' => $err,
      'err_msg_l' => $err,
      'client_id' => "0",
      'name'      => $clientName,
      'phone'     => $clientPhone,    
      'cmt_app'   => $cmtAppHTML,
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
    'err_msg_t' => "Проверка наличия клиента в БД.",
    'err_msg_s' => $err,
    'err_msg_l' => $err,
    'client_id' => "0",
    'name'      => $clientName,
    'phone'     => $clientPhone,    
        
  );
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
  return;*/
  /////////////// ВРЕМЕННО!!! ///////////////
  ///////////////////////////////////////////
  
  // - Добавление новой записи в таблицу EZ_CARS, если там ее еще нету 
  // - нету по VIN, MARK, MODEL, GENERATION их комбинаций - в зависимости от того что ввел пользователь или вообще не ввел
  if ($carVin==''||$carMark==''||$carModel==''||$generation) {
     $carSelectQuery = "select id from ez_cars where client_id = $outputEzClientID limit 1)";
  }



  $sqlCommand = "select id from ez_clients where upper(name)=upper('$clientName') and upper(phone)=upper('$clientPhone') limit 1";
  $clientSelectResult = mysqli_query($connConnection, $sqlCommand) or die (mysqli_error($connConnection));
  if (!$clientSelectResult) { //не выдал ли нам запрос ошибки 
    $err = mysqli_error($connConnection);
    $result = array(
      'err_code'  => -2101,
      'err_msg_t' => "Ошибка при попытке проверки наличия клиента.",
      'err_msg_s' => $err,
      'err_msg_l' => $err,
      'client_id' => "0",
      'name'      => $clientName,
      'phone'     => $clientPhone,
      'cmt_app'   => $cmtAppHTML,
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
    $clientInsertQuery = "insert into ez_clients (id,name,phone,mail) values (null, '$clientName','$clientPhone', 'e-mail')";
    $sqlResultInsert = mysqli_query($connConnection, $clientInsertQuery);
    if (!$sqlResultInsert) { //не выдал ли нам запрос ошибки 
      $err = mysqli_error($connConnection);
      $result = array(
        'err_code'  => -2201,
        'err_msg_t' => "Ошибка при попытке добавления клиента в БД.",
        'err_msg_s' => $err,
        'err_msg_l' => $err,
        'client_id' => "0",
        'name'      => $clientName,
        'phone'     => $clientPhone,
        'cmt_app'   => $cmtAppHTML,
      );
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    }
    $count = mysqli_affected_rows($sqlResultInsert);
    $err = "Количество добавленных записей в таблицу EZ_CLIENTS = ".$count;
    $cmtAppHTML = $cmtAppHTML."<br>".$err; //пока пишем эту хрень
    //mysqli_commit($connConnection); mysqli_close($connConnection); 
    //??? Делать ли коммит и закрывать ли сессию ???
    ///////////////////////////////////////////
    /////////////// ВРЕМЕННО!!! ///////////////
    $result = array(
      'err_code'  => 0,
      'err_msg_t' => "Добавления клиента в БД выполнено успешно.",
      'err_msg_s' => $err,
      'err_msg_l' => $err,
      'client_id' => "0",
      'name'      => $clientName,
      'phone'     => $clientPhone,    
      'cmt_app'   => $cmtAppHTML,
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
              VALUES (NULL, '$clientName','$clientPhone', '$carVin, '$carMark', '$carModel', '$generation', '$carPart', '$cmtAppHTML')";
  //$myQuery = "INSERT INTO test VALUES (NULL, '$clientName', '$clientPhone', '$count')";
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
        'name'        => $clientName
        'err_code'    => 0,
        'err_msg'     => "Заказ успешно принят! Оператор свяжется с Вами в течении 15 минут! Благодарим за обращение к нам!"
        
  );
  // ответ клиенту
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
*/
?>
