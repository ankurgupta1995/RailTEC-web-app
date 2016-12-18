<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>UIUC RailTEC</title>
  <link rel="shortcut icon" href="http://ict.uiuc.edu/railroad/favicon.ico">
  <link rel="stylesheet" type="text/css" href="../css/reset.css">
  <link rel="stylesheet" type="text/css" href="../css/base.css">
  <link rel="stylesheet" type="text/css" href="../css/tipTip.css">
  <link rel="stylesheet" type="text/css" href="../css/short-code.css">
  <link rel="stylesheet" type="text/css" href="../css/prettyPhoto.css">
  <link rel="stylesheet" type="text/css" href="../css/css3.css">
  <link rel="stylesheet" type="text/css" href="../css/slider.css">
  <script type="text/javascript" src="../js/jquery-1.6.4.min.js"></script>
  <script type="text/javascript" src="../js/jquery.nivo.slider.js"></script>
  <script type="text/javascript" src="../js/cufon-yui.js"></script>
  <script type="text/javascript" src="../js/TitilliumText.font.js"></script>
  <script type="text/javascript" src="../js/cufon-replace.js"></script>
  <script type="text/javascript" src="../js/scripts.js"></script>
  <script type="text/javascript" src="../js/custom.js"></script>
  <script type="text/javascript" src="../js/shortcode.js"></script>
  <!--
  <script type="text/javascript">
  function toggle(showHideDiv, switchTextObjectId) {
  	var ele = document.getElementById(showHideDiv);
  	var el = document.getElementById(switchTextObjectId);
      if(el.innerHTML == "More..."){
  	   el.innerHTML = "Hide...";
  	}else{
  		el.innerHTML = "More..."
  		}
  	if(ele.style.display == "block") {
      		ele.style.display = "none";

    	}
  	else {
  		ele.style.display = "block";

  	}
  }</script> -->
	<link rel="stylesheet" type="text/css" href="css/ie7.css">
</head>

<body>
  <style type="text/css">
    div.moreText {
      padding: 0px;
      margin: 0px;
      position: relative;
      top: -18px;
    }

    ul,
    li {
      text-align: center;
    }
  </style>
  <!--Header-->
  <!--UNCOMMENT THIS LATER-->
  <!--<?php include("core_header.php"); ?>-->



  <!--/Header-->
  <!--Slider-->
  <!--/Slider-->
  <!--Body content-->
  <div class="fixed-width-wrapper body-divider" id="body-content">
    <div class="full-width-wrapper">
    <!--Content-->


    <!--ANKUR. START HERE. AFTER THIS. CHOMU-->
    <form action="" method="post" enctype="multipart/form-data">
      <input type="file" name="file[]" multiple>
      <input type="submit" name="btn_submit" value="Upload Load File" />
    </form>
    <form action="search.php" method="post" class="form-horizontal">

      <fieldset>
        <!-- Form Name -->
        <legend>Criteria select</legend>

        <!-- Select Basic -->
        <div class="form-group">
          <label class="col-md-4 control-label" for="Location">Select Site</label>
          <div class="col-md-4">
            <select id="Location" name="Location" class="form-control">
              <option value="All">All</option>
              <option value="New York City">New York City</option>
              <option value="Metrolink Tangent">Metrolink Tangent</option>
            </select>
          </div>
        </div>
        <br/>
        <!-- Select Basic -->
        <div class="form-group">
          <label class="col-md-4 control-label" for="Direction">Select Direcion</label>
          <div class="col-md-4">
            <select id="Direction" name="Direction" class="form-control">
              <option value="All">All</option>
              <option value="Inbound">Inbound</option>
              <option value="Outbound">Outbound</option>
            </select>
          </div>
        </div>
        <br/> From:
        <input type="date" name="dateFrom" value="><?php echo date('Y-m-d'); ?>" />
        <br/> To:
        <input type="date" name="dateTo" value="><?php echo date('Y-m-d'); ?>" />
        <br/>
        <br/>
        <input type="submit" name="btn_submit2" value="Search file" />
      </fieldset>

    </form>


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

?>

<!--ANKUR. END HERE. BEFORE THIS. CHOMU-->



        <!--Full width-->
        <!-- Cancel comment here to announce hay seminar
    <span><strong><h2 class="sp"><strong><a href="CEE/seminar.php">Hay Seminar</a> on Friday <h5>Click title for details.</h5></strong></h2></strong></span>
Please cancel comment by cut-and-paste this line to the first line.-->
        <!--/Full width-->
    </div>
    <!--/Content-->
  </div>
  </div>
  <!--/Body content-->
  <!--/Footer panel-->
  <!--UNCOMMENT THIS LATER-->
  <!--<?php include("core_footer_panel.php"); ?> -->



  <!--/Footer panel-->
  <!--Footer Copyright-->
  <!--/Footer Copyright-->
  <!-- Start of StatCounter Code for Default Guide -->
  <!-- Start of StatCounter Code for Default Guide -->
  <script type="text/javascript">
    var sc_project = 8997880;
    var sc_invisible = 1;
    var sc_security = "4b2eea53";
    var scJsHost = (("https:" == document.location.protocol) ?
      "https://secure." : "http://www.");
    document.write("<sc" + "ript type='text/javascript' src='" +
      scJsHost +
      "statcounter.com/counter/counter.js'></" + "script>");
  </script>

  <!-- End of StatCounter Code for Default Guide -->
</body>

</html>
