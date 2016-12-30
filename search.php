<?php 


require 'sql_functions.php';

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "tecrail_db";
$conn = start_conn($servername, $username, $password, $db_name);

$city = $_POST['LocationList'];
$direction = $_POST['Direction'];
$columns = $_POST['collist'];


//$dateFrom = date('Y-m-d', strtotime($_POST['dateFrom']));
//$dateTo = date('Y-m-d', strtotime($_POST['dateTo']));

echo $city . "\n";
//echo $dateFrom . "\n";
//echo $dateTo . "\n";
echo $direction . "\n";
var_dump($columns);


//$info_array = search_function($conn, $city, $direction, $dateFrom, $dateTo);


//Give options to export
//var_dump($info_array);

close_connection($conn);

 ?>