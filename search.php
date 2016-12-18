<?php 


require 'sql_functions.php';

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "tecrail_db";
$conn = start_conn($servername, $username, $password, $db_name);

$city = $_POST['Location'];
$dateFrom = date('Y-m-d', strtotime($_POST['dateFrom']));
$dateTo = date('Y-m-d', strtotime($_POST['dateTo']));
$direction = $_POST['Direction'];

echo $city . "\n";
echo $dateFrom . "\n";
echo $dateTo . "\n";
echo $direction . "\n";


$info_array = search_function($conn, $city, $direction, $dateFrom, $dateTo);


//Give options to export
var_dump($info_array);


 ?>