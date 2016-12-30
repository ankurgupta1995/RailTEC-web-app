<?php

$method = $_SERVER['REQUEST_METHOD'];
//$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

require 'sql_functions.php';

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "tecrail_db";
$conn = start_conn($servername, $username, $password, $db_name);


switch ($method) {
	
	case 'POST':
		$ret_val = get_max_axle($conn, $_POST['location'], $_POST['datefrom'], $_POST['dateto']);
		echo $ret_val;
		break;

	default:
		# cod
		break;
}


close_connection($conn);

?>