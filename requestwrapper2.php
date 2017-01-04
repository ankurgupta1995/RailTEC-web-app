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
		/*switch ($_POST['reqType']) {
			case 'getdates':
				$ret_val = get_dates();
				break;
			case 'getaxle':
				$ret_val = get_max_axle();
				break;
			case 'gettime':
				$ret_val = get_times();
				break;
			case 'gettemp':
				$ret_val = get_temps();
				break;
			case 'getspeed':
				$ret_val = get_speeds();
				break;
			default:
				$ret_val = "reqType is invalid."
				break;
		}
		echo $ret_val;*/
		echo json_encode($_POST);
		break;

	default:
		# cod
		break;
}


close_connection($conn);

?>