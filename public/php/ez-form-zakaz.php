<?php
  //Подключаем БД = include ez-conn.php
  $filename = 'ez-conn.php';
  if (file_exists($filename)) {
    $test = array("Файл $filename существует");//echo json_encode($test, JSON_UNESCAPED_UNICODE);
  } else {
    $test = array("Файл $filename не существует");//echo json_encode($test, JSON_UNESCAPED_UNICODE);
  }
  include $filename;
  $filename = 'ez_file.php';
  if (file_exists($filename)) {
    $test = array("Файл $filename существует");//echo json_encode($test, JSON_UNESCAPED_UNICODE);
  } else {
    $test = array("Файл $filename не существует");//echo json_encode($test, JSON_UNESCAPED_UNICODE);
  }
  include $filename;
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

  function checkPhoneNumber($i_phoneNumber) {
    $f_phoneNumberDraft = $i_phoneNumber;
    $f_phoneNumberClear = preg_replace('/\s|\+|-|\(|\)/','', $f_phoneNumberDraft);//удалим пробелы,и не нужные знаки
 
    /*if ((is_numeric($phoneNumberClear))&&
        (strlen($phoneNumberClear) = 10)) { //длина чистых цифр введенного телефона должна быть равна 10 символов*/
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




  function setResultArray($i_log_key, $i_err_code, $i_err_msg_t, $i_err_msg_s, $i_err_msg_l, 
                          $i_client_name, $i_client_phone, 
                          $i_car_vin, $i_car_mark, $i_car_model, $i_car_gener, $i_car_part, 
                          $i_client_id, $i_car_id, $i_order_id, $i_cmt_app_html)
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
              'order_id'    => $i_order_id,
              'cmt_app_html'=> $i_cmt_app_html
            );
      return $f_result;
  };

  function checkQueryResult ( $i_connConnection, $i_queryResult, $i_logKey, $i_logAct, 
                              $i_errCode, $i_errMsg, $i_clientName, $i_clientPhone, 
                              $i_carVin, $i_carMark, $i_carModel, $i_carGener, $i_carPart,
                              $i_clientID, $i_carID, $i_orderID, $i_cmtAppHTML
                            ) {
    $f_sqlErr     = mysqli_error($i_connConnection);
    $f_errCode    = $i_errCode;
    $f_errMsgT    = $i_logAct;
    $f_errMsgS    = $i_errMsg;
    $f_errMsgL    = 'ERROR'.$i_errCode.'<br>'.$f_errMsgS.'<br>'.$f_sqlErr;
    $f_cmtAppHTML = $i_cmtAppHTML.'<br>'.$f_errMsgL;
    $f_result = setResultArray( $i_logKey, $f_errCode, $f_errMsgT, $f_errMsgS, $f_errMsgL,
                                $i_clientName, $i_clientPhone, 
                                $i_carVin, $i_carMark, $i_carModel, $i_carGener, $i_carPart,
                                $i_clientID, $i_carID, $i_orderID, $f_cmtAppHTML
                              );
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
  $cmtAppHTML   = "Комментарий приложения. Инициализация переменных файла.";

  $logKey       = generateRandomString();
  $cmtAppHTML   = $cmtAppHTML."<br>Приложение подключено к БД ".$dbName.".";
  $cmtAppHTML   = $cmtAppHTML."<br>Ключ журналирования: ".$logKey.".";
  mysqli_query($connConnection, 'SET NAMES utf8') or header('Location: Error');
  //printf("Host information: %s\n", mysqli_get_host_info($connConnection));
  //заполяем переменные полей формы заказа 
  if (isset($_POST["formZakazUserName"]))      { $clientName =$_POST["formZakazUserName"];}     else{ $clientName ='';}
  if (isset($_POST["formZakazContactPhone"]))  { $clientPhone=$_POST["formZakazContactPhone"];} else{ $clientPhone='';}
  if (isset($_POST["formZakazCarVIN"]))        { $carVin     =$_POST["formZakazCarVIN"];}       else{ $carVin     ='';}
  if (isset($_POST["formZakazCarMark"]))       { $carMark    =$_POST["formZakazCarMark"];}      else{ $carMark    ='';}
  if (isset($_POST["formZakazCarModel"]))      { $carModel   =$_POST["formZakazCarModel"];}     else{ $carModel   ='';}
  if (isset($_POST["formZakazCarGeneration"])) { $carGener   =$_POST["formZakazCarGeneration"];}else{ $carGener   ='';}
  // формируем комментарий приложения
  $cmtAppHTML   = $cmtAppHTML."<br>Значения переданные из формы заказа:";
  $cmtAppHTML   = $cmtAppHTML."<br>Имя клиента: ".$clientName;
  $cmtAppHTML   = $cmtAppHTML."<br>Номер телефона клиента: ".$clientPhone;
  $cmtAppHTML   = $cmtAppHTML."<br>VIN номер автомобиля: ".$carVin;
  $cmtAppHTML   = $cmtAppHTML."<br>Марка автомобиля: ".$carMark;
  $cmtAppHTML   = $cmtAppHTML."<br>Модель автомобиля: ".$carModel."<br>";
  /// !!! ??? Можно убрать ??? !!! \\\
  $logAct         = 'Получены данные из формы заказа.';
  $logKeyID       = $logKeyID + 1;
  $cmtAppHTML     = $cmtAppHTML.'<br>'.$logAct.'<br>Добавление новой записи в таблицу LOGS.<br>';
  $logInsertQuery = "insert into ez_logs (id,log_key,log_key_id,log_act,log_cmt) ".
                    "values (null, '$logKey','$logKeyID', '$logAct', '$cmtAppHTML')";
  
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

  $logAct = 'Начата проверка валидности заполнения полей Ваше имя и Ваш телефон формы заказа.'; 
  $cmtAppHTML = $cmtAppHTML.'<br>'.$logAct; 
  if ($clientName=='') {
      $errCode  = -2001;
      $errMsgT  = "Ошибка при заполнении формы заказа";
      $errMsgS  = "Поля&nbsp;&laquo;Ваше&nbsp;имя&raquo;&nbsp;и&nbsp;&laquo;Ваш&nbsp;телефон&raquo; являются&nbsp;обязательными для&nbsp;заполнения!";
      $errMsgS  = $errMsgS."<br>"."Укажите, пожалуйста, Ваше имя!";
      $errMsgL  = $errMsgS;
      $cmtAppHTML = $cmtAppHTML.'<br>'.$errMsgS; 
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
      $cmtAppHTML = $cmtAppHTML.'<br>'.$errMsgS; 
      $result = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $cmtAppHTML);
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
      $cmtAppHTML = $cmtAppHTML.'<br>'.$errMsgS; 
      $cmtAppHTML = $cmtAppHTML.
                    '<br> Номер телефона введенный пользователем: '.$phoneNumberDraft.
                    '<br> Чистый номер телефона: '.$phoneNumberClear.
                    '<br> Отформатированный номер телефона: '.$phoneNumberFormat; 
      $result = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $cmtAppHTML);
      echo json_encode($result, JSON_UNESCAPED_UNICODE);
      return;
  }
  $cmtAppHTML = $cmtAppHTML.'<br>Проверка корректности введенного номера телефона выполнена успешно.'.
                '<br> Номер телефона введенный пользователем: '.$phoneNumberDraft.
                '<br> Чистый номер телефона: '.$phoneNumberClear.
                '<br> Отформатированный номер телефона: '.$phoneNumberFormat; 

  $logAct = 'Завершена успешно проверка валидности заполнения полей Ваше имя и Ваш телефон формы заказа.'; 
  $cmtAppHTML = $cmtAppHTML.'<br>'.$logAct;
  $logAct = 'Проверка наличия клиента в базе по имени и телефону в таблице EZ_CLIENTS.'; 
  $cmtAppHTML = $cmtAppHTML.'<br>'.$logAct;
  $clientSelectQuery = "select id from ez_clients where upper(name)=upper('$clientName') and upper(phone)=upper('$clientPhone') limit 1";
  $clientSelectResult = mysqli_query($connConnection, $clientSelectQuery) or die (mysqli_error($connConnection));
  /*--- checkQueryResult BEGIN ---*/
  $messageCode = 'ERRR-21001';
  $cmtAppHTML = $cmtAppHTML.'<br>'.' Поиск сообщений для кода = '.$messageCode.'.';
  $msgArray = getMessage($messageCode); // ez_file.php
  list ($v_msg_code,$v_msg_exists,$v_msg_title,$v_msg_message1,$v_msg_message2) = $msgArray;
  $cmtAppHTML = $cmtAppHTML.'<br>'.' Поиск сообщений для кода завершен:';
  $cmtAppHTML = $cmtAppHTML.'<br>'.' v_msg_code = '.$v_msg_code;
  $cmtAppHTML = $cmtAppHTML.'<br>'.' v_msg_exists = '.$v_msg_exists;
  $cmtAppHTML = $cmtAppHTML.'<br>'.' v_msg_title = '.$v_msg_title;
  $cmtAppHTML = $cmtAppHTML.'<br>'.' v_msg_message1 = '.$v_msg_message1;
  $cmtAppHTML = $cmtAppHTML.'<br>'.' v_msg_message2 = '.$v_msg_message2;

  if (!($v_msg_code==0)) {
    $logAct   = $v_msg_title.'; v_msg_exists='.$v_msg_exists;
    $errCode  = $v_msg_code;
    $errMsg   = $v_msg_message1.'<br>'.$v_msg_message2;
    $queryResult = checkQueryResult ( $connConnection, $clientSelectResult, $logKey, $logAct, 
                                      $errCode, $errMsg, $clientName, $clientPhone, 
                                      $carVin, $carMark, $carModel, $carGener, $carPart,
                                      $clientID, $carID, $orderID, $cmtAppHTML
                                    );
    echo json_encode($queryResult, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
    return; 
   }
  /*--- checkQueryResult END ---*/                                   
  /*if (!$clientSelectResult) { //не выдал ли нам запрос ошибки 
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
  }*/
  $cmtAppHTML = $cmtAppHTML."<br>Определяем ID клиента. Ищем в выборке.";
  while ($rowEzClients = mysqli_fetch_array($clientSelectResult)) {$clientID = $rowEzClients['id'];}
  if ($clientID==0) {
    $client_exists = "Не определен ID клиента. Считаем, что в БД клиента нету";
  } else {
    $client_exists = "Определен ID клиента = ".$clientID.".";
  };  
  $logAct     = 'Завершена успешно проверка наличия клиента в базе по имени и телефону в EZ_CLIENTS.'; 
  $cmtAppHTML = $cmtAppHTML."<br>".$logAct."<br>".$client_exists;
  $logKeyID   = $logKeyID + 1;
  $logInsertQuery = "insert into ez_logs (id,log_key,log_key_id,log_act,log_cmt) ".
                    "values (null, '$logKey','$logKeyID', '$logAct', '$cmtAppHTML')";
  $logInsertQueryResult = mysqli_query($connConnection, $logInsertQuery);
  if (!$logInsertQueryResult) { //не выдал ли нам запрос ошибки 
    $sqlErr     = mysqli_error($connConnection);
    $errCode    = -2202;
    $errMsgT    = "Ошибка при заполнении журнала приложения";
    $errMsgS    = "Ошибка при попытке добавления записи ".$logKeyID." в журнал приложения:<br>".$sqlErr;
    $errMsgL    = $errMsgS."<br>".$logKeyID." - logInsertQuery = <br>".$logInsertQuery;
    $result = setResultArray( $logKey, 
                              $errCode, $errMsgT, $errMsgS, $errMsgL,
                              $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                              $clientID, $carID, $orderID, $cmtAppHTML);
    echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
    return; 
  };

  if ($clientID == 0) {
    $logAct     = 'Добавление новой записи в EZ_CLIENTS.'; 
    $cmtAppHTML = $cmtAppHTML.'<br>'.$logAct.': $clientID == 0 --> клиента там еще нету.';
    $clientInsertQuery =  "insert into ez_clients (id,name,phone,phone_draft,phone_clear,phone_format,mail) ".
                          "values (null,'$clientName','$clientPhone',".
                          "'$phoneNumberDraft','$phoneNumberClear','$phoneNumberFormat','e-mail')";
    $sqlResultInsert = mysqli_query($connConnection, $clientInsertQuery);
    if (!$sqlResultInsert) { //не выдал ли нам запрос ошибки 
      $sqlErr = mysqli_error($connConnection);
      $cmtAppHTML = $cmtAppHTML.'<br>'.'Ошибка при попытке добавления клиента в EZ_CLIENTS.'.'<br>'.$sqlErr;
      $sqlErr     = mysqli_error($connConnection);
      $errCode    = -2202;
      $errMsgT    = "Ошибка регистрации нового клиента";
      $errMsgS    = "Ошибка регистрации нового клиента:<br>".$sqlErr;
      $errMsgL    = $errMsgS;
      $cmtAppHTML = $cmtAppHTML.'<br>'.$errMsgS;
      $result = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $cmtAppHTML);
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    };  
    $cmtAppHTML = $cmtAppHTML.'<br>'.'Определяем ID добавленного клиента $clientSelectQuery определен выше.';
    $clientSelectResult = mysqli_query($connConnection, $clientSelectQuery) or die (mysqli_error($connConnection));
    if (!$clientSelectResult) { //не выдал ли нам запрос ошибки 
      $sqlErr = mysqli_error($connConnection);
      $errCode    = -2203;
      $errMsgT    = $logAct;     // 'Добавление новой записи в EZ_CLIENTS.'
      $errMsgS    = "Ошибка при попытке проверки определить ID вновь добавленного клиента.";
      $errMsgL    = $errMsgS.'<br>'.$sqlErr;
      $cmtAppHTML = $cmtAppHTML.'<br>'.$errMsgL;
      $result = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $cmtAppHTML
                                );
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    }
    $msg = "Определяем ID вновь добавленного клиента ".$clientName.".";
    $clientID = 0;
    while ($rowEzClients = mysqli_fetch_array($clientSelectResult)) { 
      $clientID = $rowEzClients['id'];
    }
    $msg = $msg."<br>"."В таблицу EZ_CLIENTS добавлен клиент с ID = ".$clientID;
    $cmtAppHTML = $cmtAppHTML."<br>".$msg;
    //mysqli_commit($connConnection); mysqli_close($connConnection); //???!!! Делать ли коммит и закрывать ли сессию ???
    
    $logAct         = 'Добавлен новый клиент в EZ_CLIENTS.';
    $logActCode     = 'CLN_INS';
    $logKeyID       = $logKeyID + 1;
    $cmtAppHTML     = $cmtAppHTML.'<br>'.$logAct.'<br>Добавление новой записи в таблицу LOGS.<br>';
    $logInsertQuery = "insert into ez_logs (id,log_key,log_key_id,log_act,log_act_code,log_cmt) ".
                      "values (null, '$logKey','$logKeyID', '$logAct', '$logActCode', '$cmtAppHTML')";
    $logInsertQueryResult = mysqli_query($connConnection, $logInsertQuery);
    if (!$logInsertQueryResult) { //не выдал ли нам запрос ошибки 
      $sqlErr     = mysqli_error($connConnection);
      $errCode    = -2203;
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
  }  

  // 5 - Добавление новой записи в таблицу EZ_CLIENT_CARS, если там ее еще нету 
  // по VIN, MARK, MODEL, GENERATION их комбинаций - в зависимости от того, что ввел пользователь
  $logAct         = "Поиск автомобиля для клиента";
  $carSelectQuery =   "select id from ez_client_cars WHERE client_id = '$clientID' ".
                      " and COALESCE(vin,'') = '$carVin' ".
                      " and COALESCE(mark,'') = '$carMark' ".
                      " and COALESCE(model,'') = '$carModel' ".
                      " and COALESCE(gener,'') = '$carGener' ".
                      " limit 1";
  $carSelectQueryResult = mysqli_query($connConnection, $carSelectQuery);
  $carSelectQueryRows = mysqli_num_rows($carSelectQueryResult); /* определение числа строк в выборке */
  if ($carSelectQueryRows>0) {
    while ($carSelectQueryResultRow = mysqli_fetch_array($carSelectQueryResult)) 
      {$carID = $carSelectQueryResultRow['id'];};
  } else {
    $carID = 0;
  }
  
  if ($carSelectQueryRows == 0) {
    $logAct         = "Добавление нового автомобиля для клиента";
    $logKeyID       = $logKeyID + 1;
    $cmtAppHTML     = $cmtAppHTML."<br>".$logAct.".";
    $carInsertQuery = "insert into ez_client_cars(id,client_id,vin,mark,model,gener) ".
                      " values (NULL,'$clientID','$carVin','$carMark','$carModel','$carGener')";
    $carInsertQueryResult = mysqli_query($connConnection, $carInsertQuery);
    if (!$carInsertQueryResult) { //не выдал ли нам запрос ошибки 
      $sqlErr     = mysqli_error($connConnection);
      $errCode    = -2301;
      $errMsgT    = $logAct;
      $errMsgS    = "Ошибка при попытке добавление нового автомобиля для клиента ID=".$clientID."<br>".$sqlErr;
      $errMsgL    = $errMsgS;
      $cmtAppHTML = $cmtAppHTML.'<br>'.$errMsgS;
      $result = setResultArray( $logKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $cmtAppHTML);
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    };                
    // определить новый созданный ez_cars.id
    $carSelectQueryResult = mysqli_query($connConnection, $carSelectQuery); 
    ///$carID = $carInsertQueryResult[0];
    $cmtAppHTML     = $cmtAppHTML."<br>".$logAct.". carID=".$carID;
    // добавить запись в EZ_LOGS
    // найти carID созданной записи в EZ_CLIENT_CARS
    $carSelectQueryResult = mysqli_query($connConnection, $carSelectQuery); 
    while ($carSelectQueryResultRow = mysqli_fetch_array($carSelectQueryResult)) 
      {$carID = $carSelectQueryResultRow['id'];};
  }
  mysqli_free_result($carSelectQueryResult); /* закрытие выборки */

 /*--- !!! ТЕСТ ВЫХОДА - BEGIN !!!*/
  $errCode    = 0;
  $errMsgT    = "ТЕСТ ВЫХОДА:: ".$logAct;
  $errMsgS    = $logAct.'. Количество выбранных записей carSelectQueryRows='.$carSelectQueryRows.
                ". Для клиента  clientID=".$clientID." определен carID=".$carID;
  $errMsgL    = $errMsgS;
  $cmtAppHTML = $cmtAppHTML.'<br>'.$errMsgS;
  $result = setResultArray( $logKey, 
                            $errCode, $errMsgT, $errMsgS, $errMsgL,
                            $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                            $clientID, $carID, $orderID, $cmtAppHTML
                          );
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
  return; 
  /*--- !!! ТЕСТ ВЫХОДА - END !!!*/




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
    $clientInsertQuery = "insert into ez_clients (id,name,phone,mail) values(null,'$clientName','$clientPhone','e-mail')";
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
    $countRows = mysqli_affected_rows($sqlResultInsert);
    $msg = "Количество добавленных записей в таблицу EZ_CLIENTS = ".$countRows;
    $cmtAppHTML = $cmtAppHTML."<br>".$msg; //пока пишем эту хрень
    //mysqli_commit($connConnection); mysqli_close($connConnection); 
    //??? Делать ли коммит и закрывать ли сессию ???
    ///////////////////////////////////////////
    /////////////// ВРЕМЕННО!!! ///////////////
    $result = array(
      'err_code'  => 0,
      'err_msg_t' => "Добавления клиента в БД выполнено успешно.",
      'err_msg_s' => $msg,
      'err_msg_l' => $msg,
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
