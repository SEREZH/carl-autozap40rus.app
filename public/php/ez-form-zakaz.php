<?php
  //ez-form-zakaz.php
  //Подключаем БД = include ez_file.php
  $filename = 'ez_file.php';
  if (file_exists($filename)) {$test = "Exist YES - $filename";} 
  else {$test = "Exist NOT - $filename";} 
  include $filename;
  putContentsLog("EZ-FORM-ZAKAZ - ----------------------- BEGIN -----------------------",100);
  putContentsLog("EZ-FORM-ZAKAZ - Included $filename",100);
  //Подключаем БД = include ez-conn.php
  $filename = 'ez-conn.php';
  if (file_exists($filename)) {$test = "Exist YES - $filename";} 
  else {$test = "Exist NOT - $filename";} 
  putContentsLog("EZ-FORM-ZAKAZ - $test",100);
  include $filename;
  putContentsLog("EZ-FORM-ZAKAZ - Included $filename",100);

  $srvHttpReferer   = $_SERVER['HTTP_REFERER'];
  $srvHttps         = array_key_exists('HTTPS',$_SERVER) ? $_SERVER['HTTPS']:'Данные недоступны';
  $srvHttpUserAgent = $_SERVER['HTTP_USER_AGENT'];
  $srvRemoteAddr    = array_key_exists('REMOTE_ADDR',$_SERVER) ? $_SERVER['REMOTE_ADDR']:'127.0.0.1'; 
  $srvRemoteHost    = array_key_exists('REMOTE_HOST',$_SERVER) ? $_SERVER['REMOTE_HOST']:gethostbyaddr($_SERVER["REMOTE_ADDR"]);
  $srvRemoteUser    = array_key_exists('REMOTE_USER',$_SERVER) ? $_SERVER['REMOTE_USER']:gethostname();
  $hostByName       = gethostbyname ($srvRemoteHost);

  putContentsLog("EZ-FORM-ZAKAZ - SERVER:srvHttpReferer=$srvHttpReferer",100);
  putContentsLog("EZ-FORM-ZAKAZ - SERVER:srvHttps=$srvHttps",100);
  putContentsLog("EZ-FORM-ZAKAZ - SERVER:srvHttpUserAgent=$srvHttpUserAgent",100);
  putContentsLog("EZ-FORM-ZAKAZ - SERVER:srvRemoteHost=$srvRemoteHost",100);
  putContentsLog("EZ-FORM-ZAKAZ - SERVER:srvRemoteAddr=$srvRemoteAddr",100);
  putContentsLog("EZ-FORM-ZAKAZ - SERVER:srvRemoteUser=$srvRemoteUser",100);
  putContentsLog("EZ-FORM-ZAKAZ - HOST:hostByName=$hostByName",100);

  /*------------------------------------------------------------------------------------------------*/
  function generateRandomString($length = 30) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    putContentsLog("EZ-FORM-ZAKAZ :: generateRandomString - randomString=$randomString",10);
    return $randomString;
  }

  function setResultArray($i_order_key, $i_err_code, $i_err_msg_t, $i_err_msg_s, $i_err_msg_l, 
                          $i_client_name, $i_client_phone, 
                          $i_car_vin, $i_car_mark, $i_car_model, $i_car_gener, $i_car_part, 
                          $i_client_id, $i_car_id, $i_order_id, $i_order_num)
  {
      $f_result = array(
              'order_key'   => $i_order_key,
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
              'order_num'   => $i_order_num
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

  function checkQueryResult ( $i_connConnection, $i_queryResult, $i_orderKey, $i_logAct, 
                              $i_errCode, $i_errMsg, $i_clientName, $i_clientPhone, 
                              $i_carVin, $i_carMark, $i_carModel, $i_carGener, $i_carPart,
                              $i_clientID, $i_carID, $i_orderID, $i_order_num
                            ) {
    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult:BEGIN",10);
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
    $f_result = setResultArray( $i_orderKey, $f_errCode, $f_errMsgT, $f_errMsgS, $f_errMsgL,
                                $i_clientName, $i_clientPhone, 
                                $i_carVin, $i_carMark, $i_carModel, $i_carGener, $i_carPart,
                                $i_clientID, $i_carID, $i_orderID, $i_order_num
                              );
    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult: f_errCode=$f_errCode",100);
    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult: f_errMsgT=$f_errMsgT",100);
    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult: f_errMsgS=$f_errMsgS",100);
    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult: f_errMsgL=$f_errMsgL",100);
    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult: f_sqlErr=$f_sqlErr",100);

    putContentsLog("EZ-FORM-ZAKAZ - checkQueryResult:END",10);

    return $f_result;
  }

  putContentsLog("EZ-FORM-ZAKAZ - Инициализация переменных пакета",10);
  $phoneNumberDraft   = "";
  $phoneNumberClear   = "";
  $phoneNumberFormat  = "";

  $orderKey     = "Ключ заказа";
  $orderKeyID   = 0;
  $logAct       = 'Выполняемое действие';
  $errCode      = 0;
  $errMsgT      = "Заголовок модального окна";
  $errMsgS      = "Текст модального окна (короткий)";
  $errMsgL      = "Текст модального окна (длинный)";
  $clientName   = "";
  $clientPhone  = "";
  $carVin       = "";
  $carMark      = "";
  $carModel     = "";
  $carGener     = "";
  $carPart      = "";  
  $clientID     = 0;
  $carID        = 0;
  $orderID      = 0;
  $orderNum     = '000000-123-456';

  $orderKey       = generateRandomString();
  mysqli_query($connConnection, 'SET NAMES utf8') or header('Location: Error');
  //printf("Host information: %s\n", mysqli_get_host_info($connConnection));
  putContentsLog("EZ-FORM-ZAKAZ - Заполение переменных пакета значениями из формы заказа",10);
  if (isset($_POST["formZakazUserName"])){ $clientName =$_POST["formZakazUserName"];} else{ $clientName ='';}
  if (isset($_POST["formZakazContactPhone"])){ $clientPhone=$_POST["formZakazContactPhone"];} else{ $clientPhone='';}
  if (isset($_POST["formZakazCarVIN"])){ $carVin=$_POST["formZakazCarVIN"];} else{ $carVin='';}
  if (isset($_POST["formZakazCarMark"])){ $carMark=$_POST["formZakazCarMark"];} else{ $carMark='';}
  if (isset($_POST["formZakazCarModel"])){ $carModel=$_POST["formZakazCarModel"];} else{ $carModel='';}
  if (isset($_POST["formZakazCarGeneration"])){ $carGener=$_POST["formZakazCarGeneration"];}else{ $carGener='';}
  putContentsLog("EZ-FORM-ZAKAZ - Ключ заказа = ".$orderKey,100);
  putContentsLog("EZ-FORM-ZAKAZ - Значения переданные из формы заказа:",100);
  putContentsLog("EZ-FORM-ZAKAZ - Имя клиента: ".$clientName,100);
  putContentsLog("EZ-FORM-ZAKAZ - Номер телефона клиента: ".$clientPhone,100);
  putContentsLog("EZ-FORM-ZAKAZ - VIN номер автомобиля: ".$carVin,100);
  putContentsLog("EZ-FORM-ZAKAZ - Марка автомобиля: ".$carMark,100);
  putContentsLog("EZ-FORM-ZAKAZ - Модель автомобиля: ".$carModel,100);
  putContentsLog("EZ-FORM-ZAKAZ - Начата проверка валидности полей Ваше имя и Ваш телефон",10);
  if ($clientName=='') {
      $errCode  = -2001;
      $errMsgT  = "Ошибка при заполнении формы заказа";
      $errMsgS  = "Поля&nbsp;&laquo;Ваше&nbsp;имя&raquo;&nbsp;и&nbsp;&laquo;Ваш&nbsp;телефон&raquo; являются&nbsp;обязательными для&nbsp;заполнения!";
      $errMsgS  = $errMsgS."<br>"."Укажите, пожалуйста, Ваше имя!";
      $errMsgL  = $errMsgS;
      putContentsLog("EZ-FORM-ZAKAZ - errCode=$errCode",100);
      putContentsLog("EZ-FORM-ZAKAZ - errMsgS=$errMsgS",100);
      putContentsLog("EZ-FORM-ZAKAZ - errMsgL=$errMsgL",100);
      $result = setResultArray( $orderKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $orderNum);
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return;
  }
  if ($clientPhone=='') {
      $errCode  = -2002;
      $errMsgT  = "Ошибка при заполнении формы заказа";
      $errMsgS  = "Поля&nbsp;&laquo;Ваше&nbsp;имя&raquo;&nbsp;и&nbsp;&laquo;Ваш&nbsp;телефон&raquo; являются&nbsp;обязательными для&nbsp;заполнения!";
      $errMsgS  = $errMsgS."<br>"."Укажите, пожалуйста, Ваш телефон!";
      $errMsgL  = $errMsgS;
      putContentsLog("EZ-FORM-ZAKAZ - errCode=$errCode",100);
      putContentsLog("EZ-FORM-ZAKAZ - errMsgS=$errMsgS",100);
      putContentsLog("EZ-FORM-ZAKAZ - errMsgL=$errMsgL",100);
      $result = setResultArray( $orderKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $orderNum);
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
      putContentsLog("EZ-FORM-ZAKAZ - errCode=$errCode",100);
      putContentsLog("EZ-FORM-ZAKAZ - errMsgS=$errMsgS",100);
      putContentsLog("EZ-FORM-ZAKAZ - errMsgL=$errMsgL",100);
      putContentsLog("EZ-FORM-ZAKAZ - phoneNumberDraft=$phoneNumberDraft; phoneNumberClear=$phoneNumberClear; ".
                     "phoneNumberFormat=$phoneNumberFormat",100);
      $result = setResultArray( $orderKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $orderNum);
      echo json_encode($result, JSON_UNESCAPED_UNICODE);
      return;
  }
  /*--------------------------------------------------------------------------------*/
  putContentsLog("EZ-FORM-ZAKAZ - EZ_CLIENT",100);
  putContentsLog("EZ-FORM-ZAKAZ - Завершена проверка валидности полей Ваше имя и Ваш телефон формы заказа",10);
  putContentsLog("EZ-FORM-ZAKAZ - phoneNumberDraft=$phoneNumberDraft; phoneNumberClear=$phoneNumberClear; ".
                 "phoneNumberFormat=$phoneNumberFormat",10);
  putContentsLog("EZ-FORM-ZAKAZ - Проверка наличия клиента по имени и телефону в EZ_CLIENTS",10);
  $clientSelectQuery = "select id from ez_clients where upper(name)=upper('$clientName') and upper(phone)=upper('$clientPhone') limit 1";
  putContentsLog("EZ-FORM-ZAKAZ - Текст запроса=$clientSelectQuery",10);
  $clientSelectResult = mysqli_query($connConnection, $clientSelectQuery) or die (mysqli_error($connConnection));
  putContentsLog("EZ-FORM-ZAKAZ - Запрос выполнен",10);
  putContentsLog("EZ-FORM-ZAKAZ - Определяем ID клиента. Ищем в выборке",10);
  while ($сlientRows = mysqli_fetch_array($clientSelectResult)) {$clientID = $сlientRows['id'];}
  if ($clientID==0) {$clientExist = "Не определен ID клиента. Считаем, что в БД клиента нету";} 
  else {$clientExist = "Определен ID клиента = $clientID";};  
  putContentsLog("EZ-FORM-ZAKAZ - $clientExist",100);

  if ($clientID == 0) {
    putContentsLog("EZ-FORM-ZAKAZ - Не определен ID клиента. Создаем нового клиента",100);
    $clientInsertQuery =  "insert into ez_clients (id,name,phone,phone_draft,phone_clear,phone_format,mail) ".
                          "values (null,'$clientName','$clientPhone',".
                          "'$phoneNumberDraft','$phoneNumberClear','$phoneNumberFormat','e-mail')";
    putContentsLog("EZ-FORM-ZAKAZ - Запрос на добавление клиента: $clientInsertQuery",10);                          
    $sqlResultInsert = mysqli_query($connConnection, $clientInsertQuery);
    if (!$sqlResultInsert) {
      $sqlErr     = mysqli_error($connConnection);
      $errCode    = -2202;
      $errMsgT    = "Ошибка регистрации нового клиента";
      $errMsgS    = "Ошибка регистрации нового клиента:<br>".$sqlErr;
      $errMsgL    = $errMsgS;
      putContentsLog("EZ-FORM-ZAKAZ - Запрос на добавление клиента выполнен с ошибкой:",100);
      putContentsLog("EZ-FORM-ZAKAZ - $sqlErr",100);
      putContentsLog("EZ-FORM-ZAKAZ - Код ошибки: $errCode",100);
      $result = setResultArray( $orderKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $orderNum);
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    };  
    putContentsLog("EZ-FORM-ZAKAZ - Запрос на добавление клиента выполнен успешно",100);
    putContentsLog("EZ-FORM-ZAKAZ - Определяем ID добавленного клиента",10);
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для определения: $clientSelectQuery",10);
    $logAct = 'Заказ обратного звонка';
    $clientSelectResult=mysqli_query($connConnection, $clientSelectQuery) or die (mysqli_error($connConnection));
    if (!$clientSelectResult) { //не выдал ли нам запрос ошибки 
      $sqlErr = mysqli_error($connConnection);
      $errCode    = -2203;
      $errMsgT    = $logAct;     // 'Добавление новой записи в EZ_CLIENTS.'
      $errMsgS    = "Ошибка при попытке определить идентификатор добавленного клиента.";
      $errMsgL    = $errMsgS.'<br>'.$sqlErr;
      putContentsLog("EZ-FORM-ZAKAZ - Запрос для определения ID клиента выполнен выполнен с ошибкой:",100);
      putContentsLog("EZ-FORM-ZAKAZ - $sqlErr",100);
      putContentsLog("EZ-FORM-ZAKAZ - Код ошибки: $errCode",100);
      $result = setResultArray( $orderKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $orderNum
                                );
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    }
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для определения ID клиента выполнен выполнен успешно",10);
    putContentsLog("EZ-FORM-ZAKAZ - Определяем ID добавленного клиента $clientName с телефоном $clientPhone",10);
    $clientID = 0;
    while ($clientRows = mysqli_fetch_array($clientSelectResult)) {$clientID = $clientRows['id'];}
    putContentsLog("EZ-FORM-ZAKAZ - В таблицу EZ_CLIENTS добавлен клиент с ID = $clientID",100);
  }  
  /*--------------------------------------------------------------------------------*/
  putContentsLog("EZ-FORM-ZAKAZ - EZ_CLIENT_CARS",100);
  putContentsLog("EZ-FORM-ZAKAZ - Поиск автомобиля для клиента в таблицу EZ_CLIENT_CARS по vin, mark, model, generation",100);
  putContentsLog("EZ-FORM-ZAKAZ - VIN=$carVin",100);
  putContentsLog("EZ-FORM-ZAKAZ - MARK=$carMark",100);
  putContentsLog("EZ-FORM-ZAKAZ - MODEL=$carModel",100);
  putContentsLog("EZ-FORM-ZAKAZ - GENERATION=$carGener",100);
  $carSelectQuery =   "select id from ez_client_cars WHERE client_id = '$clientID' ".
                      " and COALESCE(vin,'') = '$carVin' ".
                      " and COALESCE(mark,'') = '$carMark' ".
                      " and COALESCE(model,'') = '$carModel' ".
                      " and COALESCE(gener,'') = '$carGener' ".
                      " limit 1";
  putContentsLog("EZ-FORM-ZAKAZ - Запрос для выборки автомобиля клиента:",10);
  putContentsLog("EZ-FORM-ZAKAZ - $carSelectQuery",10);
  $carSelectQueryResult = mysqli_query($connConnection, $carSelectQuery);
  putContentsLog("EZ-FORM-ZAKAZ - Запрос для выборки автомобиля клиента выполнен",10);
  $carSelectQueryRowsCount = mysqli_num_rows($carSelectQueryResult); /* определение числа строк в выборке */
  putContentsLog("EZ-FORM-ZAKAZ - Выбрано автомобилей: $carSelectQueryRowsCount",10);
  if ($carSelectQueryRowsCount>0) {
    /*while ($carSelectQueryResultRow = mysqli_fetch_array($carSelectQueryResult)) 
      {$carID = $carSelectQueryResultRow['id'];};*/
    $carID  = $carSelectQueryResult->fetch_row()[0];  
  } else {
    $carID = 0;
  }
  putContentsLog("EZ-FORM-ZAKAZ - Определен ID автомобиля для клиента: $carID",100);
  if ($carSelectQueryRowsCount == 0) {
    putContentsLog("EZ-FORM-ZAKAZ - Добавление нового автомобиля для клиента",10);
    $carInsertQuery = "insert into ez_client_cars(id,client_id,vin,mark,model,gener) ".
                      " values (NULL,'$clientID','$carVin','$carMark','$carModel','$carGener')";
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для добавления нового автомобиля для клиента:",10);
    putContentsLog("EZ-FORM-ZAKAZ - $carInsertQuery",10);                  
    $carInsertQueryResult = mysqli_query($connConnection, $carInsertQuery);
    $logAct = 'Заказ обратного звонка';
    if (!$carInsertQueryResult) {
      $sqlErr     = mysqli_error($connConnection);
      $errCode    = -2301;
      $errMsgT    = $logAct;
      $errMsgS    = "Ошибка при попытке добавление нового автомобиля ";
      $errMsgL    = $errMsgS."<br>".$sqlErr;;
      putContentsLog("EZ-FORM-ZAKAZ - Запрос для добавления автомобиля для клиента выполнен с ошибкой:",100);
      putContentsLog("EZ-FORM-ZAKAZ - $sqlErr",100);
      putContentsLog("EZ-FORM-ZAKAZ - Код ошибки: $errCode",100);
      $result = setResultArray( $orderKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $orderNum);
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    };               
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для добавления нового автомобиля для клиента выполнен успешно",10); 
    putContentsLog("EZ-FORM-ZAKAZ - Определяем ID созданного автомобиля",10); 
    $carSelectQueryResult = mysqli_query($connConnection, $carSelectQuery); 
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для определения ID созданного автомобиля:",10); 
    putContentsLog("EZ-FORM-ZAKAZ - $carSelectQuery"); 
    $carSelectQueryResult = mysqli_query($connConnection, $carSelectQuery); 
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для определения ID созданного автомобиля выполнен",10);
    while ($carSelectQueryResultRow = mysqli_fetch_array($carSelectQueryResult)) 
      {$carID = $carSelectQueryResultRow['id'];};
    putContentsLog("EZ-FORM-ZAKAZ - ID созданного автомобиля определен: $carID",100);
  }
  mysqli_free_result($carSelectQueryResult); /* закрытие выборки */
  /*------------------ EZ-FORM-ZAKAZ-SIMPLE - GET_CLIENT_ORDERS_HOURL - BEGIN ------------------*/
  putContentsLog("EZ-FORM-ZAKAZ - GET_CLIENT_ORDERS_HOURLY - BEGIN clientID=$clientID",100);
  $clientOrdersHourlySelect = "select get_client_orders_hourly('$clientID')";
  $clientOrdersHourlyResult = mysqli_query($connConnection, $clientOrdersHourlySelect);
  $clientOrdersHourlyCount  = $clientOrdersHourlyResult->fetch_row()[0];
  putContentsLog("EZ-FORM-ZAKAZ - GET_CLIENT_ORDERS_HOURLY - Запрос выполнен",100);
  putContentsLog("EZ-FORM-ZAKAZ - GET_CLIENT_ORDERS_HOURLY - Количество заказов=$clientOrdersHourlyCount",100);
  mysqli_free_result($clientOrdersHourlyResult); /* закрытие выборки */
  $stopOrdersHourlyCount  = getParam("ORDERS_HOURLY_COUNT");
  putContentsLog("EZ-FORM-ZAKAZ - GET_CLIENT_ORDERS_HOURLY - stopOrdersHourlyCount=$stopOrdersHourlyCount",100);
  IF ($clientOrdersHourlyCount >= $stopOrdersHourlyCount) { 
    putContentsLog("EZ-FORM-ZAKAZ - GET_CLIENT_ORDERS_HOURLY - количество заказов за час превысило допустимое",100);
    $logAct   = 'Заказ обратного звонка';
    $sqlErr   = mysqli_error($connConnection);
    $errCode  = -2005;
    $errMsgT  = "ЗАКАЗ НЕ ПРИНЯТ";
    $errMsgS  = "Уважаемый $clientName!<br>Ваш заказ не может быть принят.".
              "<br>Количество сделанных Вами заказов превысило допустимое за промежуток времени.".
              "<br><br>Пожалуйста, попробуйте позже.";
    $errMsgL  = $errMsgS."Количество заказов в течении часа $clientOrdersHourlyCount";  
    putContentsLog("EZ-FORM-ZAKAZ - GET_CLIENT_ORDERS_HOURLY - ".$errMsgL,100);
    putContentsLog("EZ-FORM-ZAKAZ - GET_CLIENT_ORDERS_HOURLY - $sqlErr",100);
    putContentsLog("EZ-FORM-ZAKAZ - GET_CLIENT_ORDERS_HOURLY - Код ошибки: $errCode",100);
    $result = setResultArray( $orderKey, 
                              $errCode, $errMsgT, $errMsgS, $errMsgL,
                              $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                              $clientID, $carID, $orderID, $orderNum);
    echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
    return; 
  }
  /*------------------ EZ-FORM-ZAKAZ-SIMPLE - GET_CLIENT_ORDERS_HOURL - END ------------------*/
  /*------------------------------------------------------------------------------------------*/
  putContentsLog("EZ-FORM-ZAKAZ - EZ_CAR_ORDERS",100);
  putContentsLog("EZ-FORM-ZAKAZ - Добавление нового заказа для автомобиля",10);
    $orderInsertQuery = "insert into ez_car_orders(id,car_id,order_key,".
                        "srv_http_referer,srv_https,srv_http_user_agent,srv_remote_addr,".
                        "srv_remote_host,srv_remote_user,hst_host_by_name) ".
                        "values (NULL,'$carID','$orderKey',".
                        "'$srvHttpReferer','$srvHttps','$srvHttpUserAgent','$srvRemoteAddr',".
                        "'$srvRemoteHost','$srvRemoteUser','$hostByName')";
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для добавления нового заказа для автомобиля:",10);
    putContentsLog("EZ-FORM-ZAKAZ - $orderInsertQuery",10);                  
    $orderInsertQueryResult = mysqli_query($connConnection, $orderInsertQuery);
    $logAct = 'Заказ обратного звонка';
    if (!$orderInsertQueryResult) {
      $sqlErr     = mysqli_error($connConnection);
      $errCode    = -2401;
      $errMsgT    = $logAct;
      $errMsgS    = "Ошибка при попытке добавления нового заказа.";
      $errMsgL    = $errMsgS." для автомобиля ID=".$carID."<br>".$sqlErr;
      putContentsLog("EZ-FORM-ZAKAZ - Запрос для добавление нового заказа выполнен с ошибкой:",100);
      putContentsLog("EZ-FORM-ZAKAZ - $sqlErr",100);
      putContentsLog("EZ-FORM-ZAKAZ - Код ошибки: $errCode",100);
      $result = setResultArray( $orderKey, 
                                $errCode, $errMsgT, $errMsgS, $errMsgL,
                                $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                                $clientID, $carID, $orderID, $orderNum);
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      return; 
    };               
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для добавления нового заказа выполнен успешно",10); 
    putContentsLog("EZ-FORM-ZAKAZ - Определяем ID созданного заказа",10); 
    $orderSelectQuery = "select id from ez_car_orders where order_key = '$orderKey' order by created_at desc limit 1";
    putContentsLog("EZ-FORM-ZAKAZ - Текст запроса=$orderSelectQuery",10);
    $orderSelectQueryResult = mysqli_query($connConnection, $orderSelectQuery); 
    putContentsLog("EZ-FORM-ZAKAZ - Запрос для определения ID созданного заказа выполнен",10); 
    while ($orderSelectQueryResultRow = mysqli_fetch_array($orderSelectQueryResult)) 
      {$orderID = $orderSelectQueryResultRow['id'];};
    putContentsLog("EZ-FORM-ZAKAZ - ID созданного заказа определен: $orderID",100);
    $orderNum = str_pad($orderID, 6, "0", STR_PAD_LEFT);
    $orderNum = substr("$orderNum", 0, 3)."-".substr("$orderNum", 3);
    $orderNum = date( "ymd" )."-".$orderNum;
    putContentsLog("EZ-FORM-ZAKAZ - Определен Номер заказа для клиента: $orderNum",100);
    putContentsLog("EZ-FORM-ZAKAZ - Сохраняем Номер заказа в EZ_CAR_ORDERS",10); 
    $orderUpdateQuery = "update ez_car_orders set order_num = '$orderNum' where id = $orderID";
    putContentsLog("EZ-FORM-ZAKAZ - Текст запроса=$orderUpdateQuery",10);
    $orderSelectQueryResult = mysqli_query($connConnection, $orderUpdateQuery); 
    putContentsLog("EZ-FORM-ZAKAZ - Номер заказа сохранен в EZ_CAR_ORDERS",10); 


    putContentsLog("EZ-FORM-ZAKAZ - Формирование заказа завершено успешно",10); 
    $errCode    = 0;
    $errMsgT    = "ЗАКАЗ ПРИНЯТ";
    $errMsgS    = "Уважаемый $clientName, Ваш заказ принят!".
                "<br>Оператор свяжется с Вами в течении 15 минут!".
                "<br>Номер Вашего заказа: $orderNum".
                //"<br>Ключ Вашего заказа: $orderKey".
                "<span class='animated rotateIn infinite'><br>Скидка для Вашего заказа составляет <span class='ez-form-zakaz-color-red'>10%</span></span>!".
                "<br><br>Благодарим за обращение к нам!";
    $errMsgL    = $errMsgS;

    putContentsLog("EZ-FORM-ZAKAZ - errMsgS=$errMsgS",100); 


    ////////////////////////////////////////////////////
    /////////// --- TELEGRAM  BEGIN --- ////////////////
    $token  = getParam("TLG_TOKEN");  //Почему то после этого fopen не может создать поток???
    $chatId = getParam("TLG_CHAT_ID");//failed to open stream: HTTP request failed! HTTP/1.1 404 Not Found 
    ////////////////////////////////////////////////////
    $msg = "Новая заявка на сайте! \nТелефон: $clientPhone \nИмя: $clientName";
    if (!($carVin   == '')) {$msg .= "\nVIN: $carVin";}
    if (!($carMark  == '')) {$msg .= "\nМарка: $carMark";}
    if (!($carModel == '')) {$msg .= "\nМодель: $carModel";}
    if (!($carGener == '')) {$msg .= "\nПоколение: $carGener";}
    $msg = urlencode($msg);
    /*$sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chatId}&parse_mode=html&text={$msg}","r");*/
    $sendToTelegram = file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chatId}&parse_mode=html&text={$msg}","r");
    ////////////////////////////////////////////////////
    /////////// --- TELEGRAM  END --- //////////////////
    ////////////////////////////////////////////////////


    $result = setResultArray( $orderKey, 
                              $errCode, $errMsgT, $errMsgS, $errMsgL,
                              $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                              $clientID, $carID, $orderID, $orderNum
                            );
    echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
    return; 



/*--- !!! ТЕСТ ВЫХОДА - BEGIN !!!*/
/*
  $errCode    = 0;
  $errMsgT    = "ТЕСТ ВЫХОДА - ЗАКАЗ::orderID=$orderID";
  $errMsgS    = "Уважаемый $clientName, Ваш заказ принят!".
                "'<br>'Оператор свяжется с Вами в течении 15 минут!".
                "'<br>'Номер Вашего заказа: $orderID".
                "'<br>'Код Вашего заказа: $orderKey".
                "'<br>'Скидка для Вашего заказа составляет 10%!".
                "'<br>'Благодарим за обращение к нам!";
  $errMsgL    = $errMsgS;
  $result = setResultArray( $orderKey, 
                            $errCode, $errMsgT, $errMsgS, $errMsgL,
                            $clientName, $clientPhone, $carVin, $carMark, $carModel, $carGener, $carPart,
                            $clientID, $carID, $orderID, $orderNum
                          );
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
  return; 
*/
/*--- !!! ТЕСТ ВЫХОДА - END !!!*/

?>
