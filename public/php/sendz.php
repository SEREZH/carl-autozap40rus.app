<?php

  //include "senda.php";//Подключаем БД
  //Подключаем БД
  $hostname = "localhost";  // название/путь сервера, с MySQL
  $username = "carl";   // имя пользователя
  $password = "carl";   // пароль пользователя
  $dbName   = "carl01";   // название базы данных
  $myConnection = mysqli_connect($hostname, $username, $password, $dbName) or die ("could not connect to mysql");
  /* check connection */ 
  if (!$myConnection) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }
  mysqli_query($myConnection, 'SET NAMES utf8') or header('Location: Error');
  printf("Host information: %s\n", mysqli_get_host_info($myConnection));

  //делаем запрос на категории
  //$query = mysqli_query($myConnection, $sqlCommand) or die (mysqli_error($myConnection)); 
  $sqlCommand = "select name from test";
  $sqlResult = mysqli_query($myConnection, $sqlCommand) or die (mysqli_error($myConnection));
  //while ($podrow=mysqli_fetch_array($podresult)) { Что-то делаем }


  $output = array();
  while ($row = mysqli_fetch_array($sqlResult)) {
      $output[] = $row['name'];
  }
  print_r(count($output).'\n'); 
  print_r($output);

/*  function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
  }
  console_log( "sendz BEGIN" );*/

  $name = $_POST["FormDraftUserName"];
  $phonenumber = $_POST["FormDraftContactPhone"];
  $myQuery = "INSERT INTO test VALUES (NULL, '$name', '$phonenumber', 'тыц-тыц')";
  mysqli_query($myConnection, $myQuery);

  mysqli_commit($myConnection);
  mysqli_close($myConnection);

  //echo '{ "FormDraftUserName": "' . $_POST['FormDraftUserName'] . '" }';
  $result = array(
        'name' => $_POST["FormDraftUserName"],
        'phonenumber' => $_POST["FormDraftContactPhone"]
  );
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)

  /*if (isset($_POST["FormDraftUserName"]) && isset($_POST["FormDraftContactPhone"]) ) { 
    // Формируем массив для JSON ответа
      $result = array(
        'name' => $_POST["FormDraftUserName"],
        'phonenumber' => $_POST["FormDraftContactPhone"]
      ); 
      // Переводим массив в JSON
      echo json_encode($result); 
  }*/
?>
