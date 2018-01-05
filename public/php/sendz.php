<?php


	function console_log( $data ){
	  echo '<script>';
	  echo 'console.log('. json_encode( $data ) .')';
	  echo '</script>';
	}

	console_log( "sendz BEGIN" );
	//echo 'console.info("sendz BEGIN");'."NL";

	if ( isset($_POST["FormDraftUserName"]) && isset($_POST["FormDraftContactPhone"]) ) {

		$userName=$_POST['FormDraftUserName'];
		$contactPhone=$_POST['FormDraftContactPhone'];

		console_log( "$userName=".$userName );
		console_log( "$contactPhone=".$contactPhone );
 
    	$result = array('statusMessage' => 'statuscode', "statusCode" => "000");
    	echo json_encode($result); // возвращаем в json формате
  	} else {
   	// тут получается, что либо username либо password не были передены
 	    echo json_encode(array('error' => 'поля не заполнены'));
  	}


	

/*<form id ="FormDraft" name="FormDraft" action="" method="POST">    
input type="text" 
id="FormDraftUserName" 
name="FormDraftUserName" 

-------------------------------------------------
--- 1
-------------------------------------------------
$(function(){
  $('#form_send').on('click', function(event) {
    event.preventDefault();
 
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'json',
      data: $('#form_id').serialize(),
      success: function(data) {
        // тут отправляем другие данные
      },
      error: function(jqXHR, textStatus, errorThrown) {
        
      }
    });
        
  });
});

<?php 
  if ( isset($_POST["username"]) && isset($_POST["password"]) ) {
 
    // тут ваш обработчик
 
    $result = array('statusMessage' => 'statuscode', "statusCode" => "000");
    echo json_encode($result); // возвращаем в json формате
  } else {
   // тут получается, что либо username либо password не были передены
 
    echo json_encode(array('error' => 'поля не заполнены'));
  }
?>

-------------------------------------------------
--- 2
-------------------------------------------------
JavascriptВыделить код
1
2
3
4
5
6
7
8
9
10
11
12
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
<script language="javascript" type="text/javascript">
<!-- 
var ajax=null;
 
function getAjax(){
if (window.ActiveXObject) // для IE
   return new ActiveXObject("Microsoft.XMLHTTP");
else if (window.XMLHttpRequest) 
   return new XMLHttpRequest();
else {
   alert("Браузер не хавает Ajax");
   return null;
  }
}
 
function ajaxFunction(){
ajax=getAjax();
if (ajax != null) {
ajax.open("GET", 
"test.php?"+
  encodeURI(document.getElementById("in").value), 
true);
ajax.send(null);
ajax.onreadystatechange = function(){
  if(ajax.readyState==4)  
      document.getElementById("out").innerHTML=ajax.responseText;  
  }
}
}
-->
</script>
HTML5Выделить код
1
2
3
4
5
6
<form name="myform" action="" >
Input text: <input type="text" onkeyup="ajaxFunction();" 
                name="in" id="in" /><br>
                
Что передалось скрипту: <span id="out"></span>
</form>
файл test.php
PHPВыделить код
1
2
3
<?php
echo $_SERVER['REQUEST_URI'];
?>
дума
*/

/*$mysqlid=mysql_query("INSERT INTO kaissa (pole1, pole2, pole3, pole4) VALUES ('$pol1', '$pol2', '$pol3', '$pol4')");*/
?>