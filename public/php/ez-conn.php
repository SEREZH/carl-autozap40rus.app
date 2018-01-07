<?php
  namespace ez_conn;
  //Подключаем БД
  $hostname = "localhost";  // название/путь сервера, с MySQL
  $username = "carl";       // имя пользователя
  $password = "carl";       // пароль пользователя
  $dbName   = "carl01";     // название базы данных
  $connConnection = mysqli_connect($hostname, $username, $password, $dbName) or die ("Could not connect to MySql");
  /* check connection */ //тоже? - if (mysqli_connect_errno()) {
  if (!$connConnection) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    $err_conn = mysqli_connect_error();
    exit();
  }
  else{ 
    $err_conn = '0';
  }
  mysqli_query($connConnection, 'SET NAMES utf8') or header('Location: Error');
  //printf("Host information: %s\n", mysqli_get_host_info($connConnection));
?>
