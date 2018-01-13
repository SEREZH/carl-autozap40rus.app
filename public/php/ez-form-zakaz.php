<?php
  //ez-form-zakaz.php
  //Подключаем БД = include ez_file.php
  $filename = 'ez_file.php';
  if (file_exists($filename)) {$test = "Exist YES - $filename";} 
  else {$test = "Exist NOT - $filename";} 
  include $filename;
  putContentsLog("EZ-FORM-ZAKAZ - BEGIN ---------------------------------------------");
  putContentsLog("EZ-FORM-ZAKAZ - Included $filename");
  //Подключаем БД = include ez-conn.php
  $filename = 'ez-conn.php';
  if (file_exists($filename)) {$test = "Exist YES - $filename";} 
  else {$test = "Exist NOT - $filename";} 
  putContentsLog("EZ-FORM-ZAKAZ - $test");
  include $filename;
  putContentsLog("EZ-FORM-ZAKAZ - Included $filename");
  /*------------------------------------------------------------------------------------------------*/
  function generateRandomString($length = 30) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  function setResultArray($i_log_key, $i_err_code, $i_err_msg_t, $i_err_msg_s, $i_err_msg_l, 
                          $i_client_name, $i_client_phone, 
                          $i_car_vin, $i_car_mark, $i_car_model, $i_car_gener, $i_car_part, 
                          $i_client_id, $i_car_id, $i_order_id)
  {

      $f_result = array(
              'log_key'     => $i_log_key,
              'err_code'    => $i_err_code,
              'err_msg_t'   => $i_err_msg_t,
              'err_msg_s'   => $i_err_msg_s,
              'err_msg_l'   => $i_err_msg_l,
              'client_name' => $i_client_name,
              'client_phone'=> $i_client_phone,
              'car_vin'     => $i_car_vin,
              'car_mark'    => $i_car_mark,
              'car_model'   => $i_car_model,
              'car_gener'   => $i_car_gener,
              'car_part'    => $i_car_part,
              'client_id'   => $i_client_id,
              'car_id'      => $i_car_id,
              'order_id'    => $i_order_id
            );
      return $f_result;
  };

  function checkPhoneNumber($i_phoneNumber) {
    $f_phoneNumberDraft = $i_phoneNumber;
    $f_phoneNumberClear = preg_replace('/\s|\+|-|\(|\)/','', $f_phoneNumberDraft);//удалимпробелы,иненужныезнаки
     if (is_numeric($f_phoneNumberClear)) {
          $f_phoneNumberFormatPart0 = '+7';
          $f_phoneNumberFormatPart1 = substr($f_phoneNumberClear, 1, 3);
          $f_phoneNumberFormatPart2 = substr($f_phoneNumberClear, 4, 3);
          $f_phoneNumberFormatPart3 = substr($f_phoneNumberClear, 7, 2);
          $f_phoneNumberFormatPart4 = substr($f_phoneNumberClear, 9, 2);
          $f_phoneNumberFormat      = $f_phoneNumberFormatPart0.'('.
                                      $f_phoneNumberFormatPart1.')'.
                                      $f_phoneNumberFormatPart2.'-'.
                                      $f_phoneNumberFormatPart3.'-'.
                                      $f_phoneNumberFormatPart4;
    }
    return array($f_phoneNumberDraft,$f_phoneNumberClear,$f_phoneNumberFormat);
  }

  function checkQueryResult ( $i_connConnection, $i_queryResult, $i_logKey, $i_logAct, 
                              $i_errCode, $i_errMsg, $i_clientName, $i_clientPhone, 
                              $i_carVin, $i_carMark, $i_carModel, $i_carGener, $i_carPart,
                              $i_clientID, $i_carID, $i_orderID
                            ) {
    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult:BEGIN");
    $f_sqlErr     = mysqli_error($i_connConnection);
    if ($f_sqlErr) {
      $f_errCode    = $i_errCode;
      $f_errMsgT    = $i_logAct;
      $f_errMsgS    = $i_errMsg;
      $f_errMsgL    = 'ERROR'.$i_errCode.'<br>'.$f_errMsgS.'<br>'.$f_sqlErr;
    } else {
      $f_errCode    = 0;
      $f_errMsgT    = '';
      $f_errMsgS    = '';
      $f_errMsgL    = '';
    }
    $f_result = setResultArray( $i_logKey, $f_errCode, $f_errMsgT, $f_errMsgS, $f_errMsgL,
                                $i_clientName, $i_clientPhone, 
                                $i_carVin, $i_carMark, $i_carModel, $i_carGener, $i_carPart,
                                $i_clientID, $i_carID, $i_orderID
                              );
    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult: f_errCode=$f_errCode");
    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult: f_errMsgT=$f_errMsgT");
    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult: f_errMsgS=$f_errMsgS");
    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult: f_errMsgL=$f_errMsgL");
    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult: f_sqlErr=$f_sqlErr");

    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult:END");

    return $f_result;
  }


  $phoneNumberDraft   = "";
  $phoneNumberClear   = "";
  $phoneNumberFormat  = "";

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

  $logKey       = generateRandomString();
  mysqli_query($connConnection, 'SET NAMES utf8') or header('Location: Error');
  //printf("Host information: %s\n", mysqli_get_host_info($connConnection));
  //заполяем переменные полей формы заказа 
  if (isset($_POST["formZakazUserName"])){ $clientName =$_POST["formZakazUserName"];} else{ $clientName ='';}
  if (isset($_POST["formZakazContactPhone"])){ $clientPhone=$_POST["formZakazContactPhone"];} else{ $clientPhone='';}
  if (isset($_POST["formZakazCarVIN"])){ $carVin=$_POST["formZakazCarVIN"];} else{ $carVin='';}
  if (isset($_POST["formZakazCarMark"])){ $carMark=$_POST["formZakazCarMark"];} else{ $carMark='';}
  if (isset($_POST["formZakazCarModel"])){ $carModel=$_POST["formZakazCarModel"];} else{ $carModel='';}
  if (isset($_POST["formZakazCarGeneration"])){ $carGener=$_POST["formZakazCarGeneration"];}else{ $carGener='';}
  // формируем комментарий приложения
  putContentsLog("EZ-FORM-ZAKAZ - Ключ заказа = ".$logKey);
  putContentsLog("EZ-FORM-ZAKAZ - Значения переданные из формы заказа:");
  putContentsLog("EZ-FORM-ZAKAZ - Имя клиента: ".$clientName);
  putContentsLog("EZ-FORM-ZAKAZ - Номер телефона клиента: ".$clientPhone);
  putContentsLog("EZ-FORM-ZAKAZ - VIN номер автомобиля: ".$carVin);
  putContentsLog("EZ-FORM-ZAKAZ - Марка автомобиля: ".$carMark);
  putContentsLog("EZ-FORM-ZAKAZ - Модель автомобиля: ".$carModel);
  putContentsLog("EZ-FORM-ZAKAZ - Начата проверка валидности полей Ваше имя и Ваш телефон");
  if ($clientName=='') {
      $errCode  = -2001;
      $errMsgT  = "Ошибка при заполнении формы заказа";
      $errMsgS  = "Поля&nbsp;&laquo;Ваше&nbsp;имя&raquo;&nbsp;и&nbsp;&laquo;Ваш&nbsp;телефон&raquo; являются&nbsp;обязательными для&nbsp;заполнения!";
      $errMsgS  = $errMsgS."<br>"."Укажите, пожалуйста, Ваше имя!";
      $errMsgL  = $errMsgS;
      putContentsLog("EZ-FORM-ZAKAZ - errCode=$errCode");
      putContentsLog("EZ-FORM-ZAKAZ - errMsgS=$errMsgS");
      putContentsLog("EZ-FORM-ZAKAZ - errMsgL=$errMsgL");
      $result = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID);
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return;
  }
  if ($clientPhone=='') {
      $errCode  = -2002;
      $errMsgT  = "Ошибка при заполнении формы заказа";
      $errMsgS  = "Поля&nbsp;&laquo;Ваше&nbsp;имя&raquo;&nbsp;и&nbsp;&laquo;Ваш&nbsp;телефон&raquo; являются&nbsp;обязательными для&nbsp;заполнения!";
      $errMsgS  = $errMsgS."<br>"."Укажите, пожалуйста, Ваш телефон!";
      $errMsgL  = $errMsgS;
      putContentsLog("EZ-FORM-ZAKAZ - errCode=$errCode");
      putContentsLog("EZ-FORM-ZAKAZ - errMsgS=$errMsgS");
      putContentsLog("EZ-FORM-ZAKAZ - errMsgL=$errMsgL");
      $result = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID);
      echo json_encode($result, JSON_UNESCAPED_UNICODE);
      return;
  } 
  $phoneNumberArray = checkPhoneNumber($clientPhone);
  list  ($phoneNumberDraft, $phoneNumberClear, $phoneNumberFormat) = $phoneNumberArray;
  if (!$phoneNumberFormat) {
      $errCode    = -2003;
      $errMsgT    = "Ошибка при заполнении формы заказа";
      //$errMsgS    = "Поле&nbsp;&laquo;Ваш&nbsp;телефон&raquo; должно содержать 10 цифр!";
      $errMsgS    = $errMsgS."<br>"."Пожалуйста, введите правильно Ваш телефон!";
      $errMsgL    = $errMsgS;
      putContentsLog("EZ-FORM-ZAKAZ - errCode=$errCode");
      putContentsLog("EZ-FORM-ZAKAZ - errMsgS=$errMsgS");
      putContentsLog("EZ-FORM-ZAKAZ - errMsgL=$errMsgL");
      putContentsLog("EZ-FORM-ZAKAZ - phoneNumberDraft=$phoneNumberDraft; phoneNumberClear=$phoneNumberClear; ".
                     "phoneNumberFormat=$phoneNumberFormat");
      $result = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID);
      echo json_encode($result, JSON_UNESCAPED_UNICODE);
      return;
  }
  putContentsLog("EZ-FORM-ZAKAZ - Завершена проверка валидности полей Ваше имя и Ваш телефон формы заказа");
  putContentsLog("EZ-FORM-ZAKAZ - phoneNumberDraft=$phoneNumberDraft; phoneNumberClear=$phoneNumberClear; ".
                 "phoneNumberFormat=$phoneNumberFormat");
  putContentsLog("EZ-FORM-ZAKAZ - Проверка наличия клиента по имени и телефону в EZ_CLIENTS");
  $clientSelectQuery = "select id from ez_clients where upper(name)=upper('$clientName') and upper(phone)=upper('$clientPhone') limit 1";
  putContentsLog("EZ-FORM-ZAKAZ - Текст запроса=$clientSelectQuery");
  $clientSelectResult = mysqli_query($connConnection, $clientSelectQuery) or die (mysqli_error($connConnection));
  putContentsLog("EZ-FORM-ZAKAZ - Запрос выполнен");
  putContentsLog("EZ-FORM-ZAKAZ - Определяем ID клиента. Ищем в выборке");
  while ($сlientRows = mysqli_fetch_array($clientSelectResult)) {$clientID = $сlientRows['id'];}
  if ($clientID==0) {$clientExist = "Не определен ID клиента. Считаем, что в БД клиента нету";} 
  else {$clientExist = "Определен ID клиента = $clientID";};  
  putContentsLog("EZ-FORM-ZAKAZ - $clientExist");

  if ($clientID == 0) {
    putContentsLog("EZ-FORM-ZAKAZ - Не определен ID клиента. Создаем нового клиента");
    $clientInsertQuery =  "insert into ez_clients (id,name,phone,phone_draft,phone_clear,phone_format,mail) ".
                          "values (null,'$clientName','$clientPhone',".
                          "'$phoneNumberDraft','$phoneNumberClear','$phoneNumberFormat','e-mail')";
    putContentsLog("EZ-FORM-ZAKAZ - Запрос на добавление клиента: $clientInsertQuery");                          
    $sqlResultInsert = mysqli_query($connConnection, $clientInsertQuery);
    if (!$sqlResultInsert) {
      $sqlErr     = mysqli_error($connConnection);
      $errCode    = -2202;
      $errMsgT    = "Ошибка регистрации нового клиента";
      $errMsgS    = "Ошибка регистрации нового клиента:<br>".$sqlErr;
      $errMsgL    = $errMsgS;
      putContentsLog("EZ-FORM-ZAKAZ - Запрос на добавление клиента выполнен с ошибкой:");
      putContentsLog("EZ-FORM-ZAKAZ - $sqlErr");
      putContentsLog("EZ-FORM-ZAKAZ - Код ошибки: $errCode");
      $result = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID);
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    };  
    putContentsLog("EZ-FORM-ZAKAZ - Запрос на добавление клиента выполнен успешно");
    putContentsLog("EZ-FORM-ZAKAZ - Определяем ID добавленного клиента");
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для определения: $clientSelectQuery");
    $clientSelectResult=mysqli_query($connConnection, $clientSelectQuery) or die (mysqli_error($connConnection));
    if (!$clientSelectResult) { //не выдал ли нам запрос ошибки 
      $sqlErr = mysqli_error($connConnection);
      $errCode    = -2203;
      $errMsgT    = $logAct;     // 'Добавление новой записи в EZ_CLIENTS.'
      $errMsgS    = "Ошибка при попытке проверки определить ID вновь добавленного клиента.";
      $errMsgL    = $errMsgS.'<br>'.$sqlErr;
      putContentsLog("EZ-FORM-ZAKAZ - Запрос для определения ID клиента выполнен выполнен с ошибкой:");
      putContentsLog("EZ-FORM-ZAKAZ - $sqlErr");
      putContentsLog("EZ-FORM-ZAKAZ - Код ошибки: $errCode");
      $result = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID
                                );
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    }
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для определения ID клиента выполнен выполнен успешно");
    putContentsLog("EZ-FORM-ZAKAZ - Определяем ID добавленного клиента $clientName с телефоном $clientPhone");
    $clientID = 0;
    while ($clientRows = mysqli_fetch_array($clientSelectResult)) {$clientID = $clientRows['id'];}
    putContentsLog("EZ-FORM-ZAKAZ - В таблицу EZ_CLIENTS добавлен клиент с ID = $clientID");
  }  
  putContentsLog("EZ-FORM-ZAKAZ - Поиск автомобиля для клиента в таблице EZ_CLIENT_CARS по VIN, MARK, MODEL, GENERATION");
  putContentsLog("EZ-FORM-ZAKAZ - VIN=$carVin");
  putContentsLog("EZ-FORM-ZAKAZ - MARK=$carMark");
  putContentsLog("EZ-FORM-ZAKAZ - MODEL=$carModel");
  putContentsLog("EZ-FORM-ZAKAZ - GENERATION=$carGener");
  $carSelectQuery =   "select id from ez_client_cars WHERE client_id = '$clientID' ".
                      " and COALESCE(vin,'') = '$carVin' ".
                      " and COALESCE(mark,'') = '$carMark' ".
                      " and COALESCE(model,'') = '$carModel' ".
                      " and COALESCE(gener,'') = '$carGener' ".
                      " limit 1";
  putContentsLog("EZ-FORM-ZAKAZ - Запрос для выборки автомобиля клиента:");
  putContentsLog("EZ-FORM-ZAKAZ - $carSelectQuery");
  $carSelectQueryResult = mysqli_query($connConnection, $carSelectQuery);
  putContentsLog("EZ-FORM-ZAKAZ - Запрос для выборки автомобиля клиента выполнен");
  $carSelectQueryRowsCount = mysqli_num_rows($carSelectQueryResult); /* определение числа строк в выборке */
  putContentsLog("EZ-FORM-ZAKAZ - Выбрано автомобилей: $carSelectQueryRowsCount");
  if ($carSelectQueryRowsCount>0) {
    while ($carSelectQueryResultRow = mysqli_fetch_array($carSelectQueryResult)) 
      {$carID = $carSelectQueryResultRow['id'];};
  } else {
    $carID = 0;
  }
  putContentsLog("EZ-FORM-ZAKAZ - Определен ID автомобиля для клиента: $carID");
  if ($carSelectQueryRowsCount == 0) {
    putContentsLog("EZ-FORM-ZAKAZ - Добавление нового автомобиля для клиента");
    $carInsertQuery = "insert into ez_client_cars(id,client_id,vin,mark,model,gener) ".
                      " values (NULL,'$clientID','$carVin','$carMark','$carModel','$carGener')";
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для добавления нового автомобиля для клиента:");
    putContentsLog("EZ-FORM-ZAKAZ - $carInsertQuery");                  
    $carInsertQueryResult = mysqli_query($connConnection, $carInsertQuery);
    if (!$carInsertQueryResult) {
      $sqlErr     = mysqli_error($connConnection);
      $errCode    = -2301;
      $errMsgT    = $logAct;
      $errMsgS    = "Ошибка при попытке добавление нового автомобиля для клиента ID=".$clientID."<br>".$sqlErr;
      $errMsgL    = $errMsgS;
      putContentsLog("EZ-FORM-ZAKAZ - Запрос для добавления нового автомобиля для клиента выполнен с ошибкой:");
      putContentsLog("EZ-FORM-ZAKAZ - $sqlErr");
      putContentsLog("EZ-FORM-ZAKAZ - Код ошибки: $errCode");
      $result = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID);
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    };               
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для добавления нового автомобиля для клиента выполнен успешно"); 
    putContentsLog("EZ-FORM-ZAKAZ - Определяем ID созданного автомобиля"); 
    $carSelectQueryResult = mysqli_query($connConnection, $carSelectQuery); 
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для определения ID созданного автомобиля:"); 
    putContentsLog("EZ-FORM-ZAKAZ - $carSelectQuery"); 
    $carSelectQueryResult = mysqli_query($connConnection, $carSelectQuery); 
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для определения ID созданного автомобиля выполнен");
    while ($carSelectQueryResultRow = mysqli_fetch_array($carSelectQueryResult)) 
      {$carID = $carSelectQueryResultRow['id'];};
    putContentsLog("EZ-FORM-ZAKAZ - ID созданного автомобиля определен: $carID");
  }
  mysqli_free_result($carSelectQueryResult); /* закрытие выборки */

 /*--- !!! ТЕСТ ВЫХОДА - BEGIN !!!*/
  $errCode    = 0;
  $errMsgT    = "ТЕСТ ВЫХОДА";
  $errMsgS    = "Для клиента  clientID=".$clientID." определен carID=".$carID;
  $errMsgL    = $errMsgS;
  $result = setResultArray( $logKey, 
                            $errCode, $errMsgT, $errMsgS, $errMsgL,
                            $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                            $clientID, $carID, $orderID
                          );
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
  return; 
  /*--- !!! ТЕСТ ВЫХОДА - END !!!*/

  

/*
'err_msg'     => "Заказ успешно принят! Оператор свяжется с Вами в течении 15 минут! Благодарим за обращение к нам!"

  // добавление новой записи (заказа) в таблицу DRAFTS
  $myQuery = "INSERT INTO drafts (id,client,phone,vin,mark,model,generation,part,cmt_app_client) 
              VALUES (NULL, '$clientName','$clientPhone', '$carVin, '$carMark', '$carModel', '$generation', '$carPart')";
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
