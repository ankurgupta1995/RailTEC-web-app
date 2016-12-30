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
	case 'GET':
		$ret_val = find_unique_locations($conn);
		echo $ret_val;
		break;
	
	case 'POST':
		$ret_val = get_columns($conn, $_POST['location']);
		echo $ret_val;
		break;

	default:
		# cod
		break;
}


close_connection($conn);

?>