<?php

require 'file_parser.php';
require 'sql_functions.php';

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "tecrail_db";


$conn = start_conn($servername, $username, $password, 0);
db_creator($conn, $db_name);
$conn = start_conn($servername, $username, $password, $db_name);
train_info($conn);

if (isset($_FILES['file']) || !(empty($_FILES['name']))) {
    $count = 0;
    $files = $_FILES['file'];
    //print_r($files);
    foreach ($files['tmp_name'] as $file) {
        $uploaded_file = file($file, FILE_IGNORE_NEW_LINES);
        $data_from_file = parser($uploaded_file);

        //print_r($data_from_file);
        //return;
        unlink($file);
        $new_id = insert_train_info($conn, $data_from_file['site'], $data_from_file['date_time'], floatval($data_from_file['speed']), $data_from_file['direction'], $data_from_file['fault'], floatval($data_from_file['ambient_temp']), floatval($data_from_file['base_temp']), floatval($data_from_file['top_temp']));
        if (!($new_id === -1)) {
            insert_peaks_peaksloc($conn, intval($new_id), $data_from_file['site'], $data_from_file['columns'], $data_from_file['peaks'], $data_from_file['peaks_loc']);
        }
        $count++;
    }

    echo "Uploaded " . $count ." files.";
} else {
    echo "Please select file(s) to upload.";
}
$conn->close();

echo "<br><a href=\"javascript:history.go(-1)\">GO BACK</a>";

?>
