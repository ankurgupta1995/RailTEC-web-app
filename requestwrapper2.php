<?php

$method = $_SERVER['REQUEST_METHOD'];
//$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

require 'sql_functions.php';

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "tecrail_db";
$conn = start_conn($servername, $username, $password, $db_name);




//MAKE A DOUBLE PHP WRAPPER FOR POST REQUESTS. USE NUMBERS/STRINGS/WHATEVER.

switch ($method) {
	
	case 'POST':
		switch ($_POST['reqType']) {
			case 'getdates':
				$ret_val = get_dates($conn, $_POST['location'], $_POST['dir']);
				break;
			case 'getaxle':
				$ret_val = get_max_axle($conn, $_POST['location'], $_POST['dir'], $_POST['datefrom'], $_POST['dateto'],
										$_POST['timefrom'], $_POST['timeto'], $_POST['tempfrom'], $_POST['tempto'], 
										$_POST['speedfrom'], $_POST['speedto']);
				break;
			case 'gettime':
				$ret_val = get_times($conn, $_POST['location'], $_POST['dir'], $_POST['datefrom'], $_POST['dateto']);
				break;
			case 'gettemp':
				$ret_val = get_temps($conn, $_POST['location'], $_POST['dir'], $_POST['datefrom'], $_POST['dateto'],
									 $_POST['timefrom'], $_POST['timeto']);
				break;
			case 'getspeed':
				$ret_val = get_speeds($conn, $_POST['location'], $_POST['dir'], $_POST['datefrom'], $_POST['dateto'],
									  $_POST['timefrom'], $_POST['timeto'], $_POST['tempfrom'], $_POST['tempto']);
				break;
			case 'search':
				$ret_val = search_db($conn, json_decode($_POST['data'], true));
				break;
			default:
				$ret_val = "reqType is invalid.";
				break;
		}
		echo json_encode($ret_val);
		break;

	default:
		# cod
		break;
}


close_connection($conn);

?>