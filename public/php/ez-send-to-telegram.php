<?php
//ez-send-to-telegram.php

function sendToTelegramTest($i_clientName, $i_phoneNumber) {
  return  $i_clientName;
}  


function sendToTelegram($i_clientName, $i_phoneNumber) {
    $f_clientName = $i_clientName;
    $f_clientNameFieldset = "Имя пославшего: ";
    $f_phoneNumber = $i_phoneNumber;
    $f_phoneNumberFieldset = "Телефон: ";
    $f_theme = $i_phoneNumber;
    $f_themeFieldset = "Тема: ";


    $token = "533993012:AAHtelmGfqThW099Bj0tzjLTSrTdgqrKHLY";
    $chatId = "-265745077";
    /*$arr = array(
      $f_clientNameFieldset => $f_clientName,
      $f_phoneNumberFieldset => $f_phoneNumber,
      $f_themeFieldset => $f_theme
    );
    foreach($arr as $key => $value) {
      $txt .= "<b>".$key."</b> ".$value."%0A";
    };
    $sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chatId}&parse_mode=html&text={$txt}","r");
    if ($sendToTelegram) {
      //echo '<p class="success">Спасибо за отправку вашего сообщения!</p>';
      return true;
    } else {
      //echo '<p class="fail"><b>Ошибка. Сообщение не отправлено!</b></p>';
      return false;
    }*/
    //Генерируем сообщение, которое хотим отправить в Telegram. Опять же, для примера:
    //$msg = "Новая заявка на сайте! \nE-mail: ezhaksm@mail.ru \nТелефон: $f_phoneNumber \n Имя: $f_clientName";
    //Затем необходимо отправить эти данные в телеграм. Делать это можно разными способами, самый простой:
    //$token = *Вставь сюда токен своего бота*;
    //$telegram_admin_id = *Сюда твой ID, взятый из userinfobot*;
    //$msg = "Новая заявка на сайте! \nE-mail: $email \nТелефон: $phone \n Имя: $name";
    


    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'header'=> "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36\r\n"
      )
    );
    $context = stream_context_create($opts);

    $msg = "Новая заявка на сайте! \nE-mail: ezhaksm@mail.ru \nТелефон: $f_phoneNumber \n Имя: $f_clientName";
    //file_get_contents("https://api.telegram.org/bot'. $token .'/sendMessage?chat_id='. $chatId .'&text=' . urlencode($msg)");

    $file = file_get_contents("https://api.telegram.org/bot'. $token .'/sendMessage?chat_id='. $chatId .'&text=' . urlencode($msg)", false, $context);

    



    /*} else {
      echo '<p class="fail">Ошибка. Вы заполнили не все обязательные поля!</p>';
    }
    } else {
    header ("Location: /");
    }*/
    //return array($f_phoneNumberDraft,$f_phoneNumberClear,$f_phoneNumberFormat);
 }
?>