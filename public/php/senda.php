<?
$hostname = "localhost"; 	// название/путь сервера, с MySQL
$username = "carl"; 	// имя пользователя
$password = "carl"; 	// пароль пользователя
$dbName = "carl01"; 	// название базы данных

//$link = mysqli_connect("localhost", "my_user", "my_password", "world");
//$myConnection = mysqli_connect("$db_host","$db_username","$db_pass","$db_name") or die ("could not connect to mysql"); 
$myConnection = mysqli_connect($hostname, $username, $password, $dbName) or die ("could not connect to mysql");
/* check connection */ 
if (!$myConnection) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
printf("Host information: %s\n", mysqli_get_host_info($myConnection));
/* close connection */
/////mysqli_close($link);


/* Создаем соединение */
//mysql_connect($hostname, $username, $password) or die ("Не могу создать соединение");
//mysql_query('SET NAMES utf8') or header('Location: Error');
/* Выбираем базу данных. Если произойдет ошибка - вывести ее */
//mysql_select_db($dbName) or die (mysql_error());
?>