<?php
  function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
  }
  console_log( "sendz BEGIN" );
  echo "sendz BEGIN";

  if (isset($_POST["FormDraftUserName"]) && isset($_POST["FormDraftContactPhone"]) ) { 
    // Формируем массив для JSON ответа
      $result = array(
        'name' => $_POST["FormDraftUserName"],
        'phonenumber' => $_POST["FormDraftContactPhone"]
      ); 
      // Переводим массив в JSON
      echo json_encode($result); 
  }
?>
