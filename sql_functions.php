<?php

/*$servername = "localhost";
$username = "root";
$password = "";*/

//---------------------------------------------DB CONNECTION AND TABLE CREATION----------------------------------------------------------------------

//*************************************************MAKING CONNECTION***************************************************
function start_conn($servername, $username, $password, $DBname)
{
  if(!$DBname)
  {
    $conn = new mysqli($servername, $username, $password);
  }
  else
  {
    $conn = new mysqli($servername, $username, $password, $DBname);
  }

  if ($conn->connect_error)
  {
    die("connection_failed: " . $conn->connect_error);
  }
  return $conn;
}
//*************************************************************************************************************************


//echo "Line 17";

//**************************************************CREATING DATABASE********************************************************
function db_creator($conn, $dbname)
{
  $sql = "CREATE DATABASE " . $dbname;
  if($conn->query($sql) === TRUE)
  {
    echo "Database Created Successfully";
  }
  else 
  {
    if (strpos($conn->error, "database exists") === FALSE)
    {
      echo "Error creating DB: " . $conn->error;
    }
  }


  $conn->commit();
  $conn->close();
}
//***********************************************************************************************************************************




//*********************************************CREATING TABLES*********************************************************************
//train information
function train_info($conn)
{
  $sql = "CREATE TABLE trainInfo(
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  site VARCHAR(256) NOT NULL,
  t_date DATE NOT NULL,
  t_time TIME NOT NULL,
  speed FLOAT NOT NULL,
  dir VARCHAR(10) NOT NULL,
  num_faults INT DEFAULT 0,
  gauge_fault VARCHAR(256),
  ambient_temp FLOAT DEFAULT 0.0,
  top_temp FLOAT DEFAULT 0.0,
  base_temp FLOAT DEFAULT 0.0)";

  if($conn->query($sql) === TRUE)
  {
    echo "Table Created Successfully";
  }
  else
  {
    if(strpos($conn->error, "exists") === FALSE)
    {
      echo "Error creating table: " . $conn->error;
    }
  }

  $conn->commit();
}


//peak table
function peaks_peaksloc($conn, $loc, $columns)
{

  //location has space.
  if(strlen($loc) > 1)
  {
    $new_loc = explode(" ", $loc);
    $location = $new_loc[0];
    for($j = 1; $j<sizeof($new_loc); $j++)
    {
      $location .= "_" . $new_loc[$j];
    }
  }

  $sql = "CREATE TABLE " . $location . "_peaks(
  id BIGINT UNSIGNED NOT NULL,
  axle INT UNSIGNED NOT NULL";

  $column_array = explode(",", $columns);

  foreach ($column_array as $col_name)
  {
    $sql .= ", " . $col_name . " FLOAT NOT NULL";
  }

  $sql .= ")";
  ////echo $sql;


  if($conn->query($sql) === TRUE)
  {
    echo "Table Created Successfully";
  }
  else
  {
    if(strpos($conn->error, "exists") === FALSE)
    {
      echo "Error creating table: " . $conn->error;
    }
  }

  $conn->commit();


  $sql = "CREATE TABLE " . $location . "_peaks_loc(
  id BIGINT UNSIGNED NOT NULL,
  axle INT UNSIGNED NOT NULL";


  $column_array = explode(",", $columns);

  foreach ($column_array as $col_name)
  {
    $sql .= ", " . $col_name . " INT NOT NULL";
  }

  $sql .= ")";
  ////echo $sql;

  if($conn->query($sql) === TRUE)
  {
    echo "Table Created Successfully";
  }
  else
  {
    if(strpos($conn->error, "exists") === FALSE)
    {
      echo "Error creating table: " . $conn->error;
    }
  }

  $conn->commit();
}


//*******************************************************************************************************************


//------------------------------------------------------------------------------------------------------------------------------------------
//Helper functions

function convert_date_time($date_time)
{
  $input = explode(",", $date_time);

  $date = $input[0];
  if(strlen($input[1]) === 1)
    $date .= "-0" . $input[1];
  else
    $date .= "-" . $input[1];

  if(strlen($input[2]) === 1)
    $date .= "-0" . $input[2];
  else
    $date .= "-" . $input[2];

  if(strlen($input[3]) === 1)
    $time = "0" . $input[3];
  else
    $time = $input[3];

  if(strlen($input[4]) === 1)
    $time .= ":0" . $input[4];
  else
    $time .= ":" . $input[4];

  if(strlen($input[5]) === 1)
    $time .= ":0" . $input[5];
  else
    $time .= ":" . $input[5];

  return array("date" => $date, "time" => $time);

}

function calculate_num_faults($gauge_fault)
{
  $faults = explode(",", $gauge_fault);
  $count = 0;
  foreach($faults as $f)
  {
    if ($f === "1")
      $count += 1;
  }

  return $count;
}


//-----------------------------------------------------MySQL COMMAND FUNCTIONS---------------------------------------------------------------


//*******************************************FUNCTIONS TO UPLOAD DATA********************************************************
//upload new train data if num_faults is lesser than the old one.
function insert_train_info($conn, $site, $t_date_time, $speed, $dir, $gauge_fault, $amb_temp, $top_temp, $base_temp)
{
  //code here
  //format date and time correctly
  //change previously available data depending on num_faults'
  //calculate num_faults from gauge_fault
   $date_time = convert_date_time($t_date_time);
   $t_date = $date_time["date"];
   $t_time = $date_time["time"];
   $num_faults = calculate_num_faults($gauge_fault);

   $sql = "SELECT num_faults, id FROM trainInfo WHERE site = '$site' AND t_date LIKE '$t_date%' AND t_time LIKE '%$t_time'";
   $result = $conn->query($sql);

   if($result->num_rows > 0)
   {
    $row = $result->fetch_assoc();
    if($num_faults <= $row["num_faults"])
    {
      //delete old record from here and from peaks and peaksloc
      $sql = "DELETE FROM trainInfo WHERE id = '" . $row['id'] . "'";
      if($conn->query($sql) === FALSE)
      {
        echo "Error replacing: " . $conn->error;
      }
      delete_peaks_peaks_loc($conn, $row['id'], $site);
    }
    else
      return -1;
   }

   $new_sql = "INSERT INTO trainInfo(site, t_date, t_time, speed, dir, num_faults, gauge_fault, ambient_temp, base_temp, top_temp) VALUES ('$site', '$t_date', '$t_time', '$speed', '$dir', '$num_faults', '$gauge_fault', $amb_temp, $base_temp, $top_temp)";

   if($conn->query($new_sql) === FALSE)
   {
    echo "Error: " .$sql . "<br>" . $conn->error;
   }

   $last_id = $conn->insert_id;

   $conn->commit();


  return $last_id;
}


function delete_peaks_peaks_loc($conn, $id, $loc)
{
  if(strlen($loc) > 1)
  {
    $new_loc = explode(" ", $loc);
    $location = $new_loc[0];
    for($j = 1; $j<sizeof($new_loc); $j++)
    {
      $location .= "_" . $new_loc[$j];
    }
  }
  $sql = "DELETE FROM " . $location . "_peaks WHERE id = '" .$id . "'";
  if($conn->query($sql) === FALSE)
  {
    echo "Error deleting from peaks: " . $conn->error;
  }
  $conn->commit();
  $sql = "DELETE FROM " . $location . "_peaks_loc WHERE id = '" .$id . "'";
  if($conn->query($sql) === FALSE)
  {
    echo "Error deleting from peaks_loc: " . $conn->error;
  }
  $conn->commit();
  return;
}



//data should be a list of tuple: [(axle, peaks data, peak_loc data), (axle, peaks data, peak_loc data),......]
function insert_peaks_peaksloc($conn, $id, $loc, $columns, $peaks_data, $peaksloc_data)
{
  //check if table exists.
  //if false, create the tables using the function
  //enter data into table
  //pretty straightforward (maybe 3-4 hours of work PLEASE DO IT!!!!!)
  //get axle count using a for loop.
  if(strlen($loc) > 1)
  {
    $new_loc = explode(" ", $loc);
    $location = $new_loc[0];
    for($j = 1; $j<sizeof($new_loc); $j++)
    {
      $location .= "_" . $new_loc[$j];
    }
  }

  $sql = "SHOW TABLES LIKE '" . $location . "_peaks'";

  $result = $conn->query($sql);
  if($result->num_rows === 0)
    peaks_peaksloc($conn, $location, $columns);

  $axle_count = 0;
  foreach ($peaks_data as $val)
  {
    $sql = "INSERT INTO " . $location . "_peaks(id, axle, " . $columns . ") VALUES ($id, $axle_count";
    $data = explode(",", $val);
    for($i = 0; $i<sizeof($data); $i++)
    {
      $sql .= ", $data[$i]";
    }
    $sql .= ")";
    ////echo $sql;
    if($conn->query($sql) === FALSE)
      //echo "Peaks filled with new record";
      echo "connection_error: " . $conn->error;
      //echo "Error filling peaks record at axle_count = $axle_count";
    $axle_count += 1;
  }

  $axle_count = 0;
  foreach ($peaksloc_data as $val)
  {
    $sql = "INSERT INTO " . $location . "_peaks_loc(id, axle, " . $columns . ") VALUES ($id, $axle_count";
    $data = explode(",", $val);
    for($i = 0; $i<sizeof($data); $i++)
    {
      $sql .= ", $data[$i]";
    }
    $sql .= ")";
    ////echo $sql;
    if($conn->query($sql) === FALSE)
      //echo "Peaks_loc filled with new record";
      echo "connection_error: " . $conn->error; 
      //echo "Error filling peaks_loc record at axle_count = $axle_count";
    $axle_count += 1;
  }

  $conn->commit();

  return;

}


//****************************************SEARCH FUNCTIONS***********************************************************************
function search_function($conn, $site, $dir, $start_date, $end_date)
{
  $ret_array = Array();
  if($site === "All")
  {
    $loc_array = Array("Metrolink Tangent", "New York City");
  }
  else
  {
    $loc_array = Array($site);
  }

  foreach($loc_array as $loc)
  {
      if(strlen($loc) > 1)
      {
        $new_loc = explode(" ", $loc);
        $location = $new_loc[0];
        for($j = 1; $j<sizeof($new_loc); $j++)
        {
          $location .= "_" . $new_loc[$j];
        }
      }
      if ($dir === "All")
      {
        $sql = "SELECT * FROM trainInfo as T1 INNER JOIN " . $location . "_peaks as T2 ON T1.id = T2.id WHERE date_format(T1.t_date, '%Y-%m-%d') BETWEEN  '". $start_date ."' AND '". $end_date ."'";
        $result = $conn->query($sql);
        if($result->num_rows > 0)
        {
          while($row = $result->fetch_assoc())
          {
            array_push($ret_array, $row);
          }
        }
      }
      else
      {
        $sql = "SELECT * FROM trainInfo as T1 INNER JOIN " . $location . "_peaks as T2 ON T1.id = T2.id WHERE T1.dir = '" . $dir . "' AND date_format(T1.t_date, '%Y-%m-%d') BETWEEN  '". $start_date ."' AND '". $end_date ."'";
        $result = $conn->query($sql);
        if($result->num_rows > 0)
        {
          while($row = $result->fetch_assoc())
          {
            array_push($ret_array, $row);
          }
        }
      }
  }

  return $ret_array;

}

function find_unique_locations($conn)
{
  $ret_array = Array();
  $sql = "SELECT DISTINCT site FROM trainInfo";
  $result = $conn->query($sql);
  if($result->num_rows>0)
  {
    while($row = $result->fetch_assoc())
    {
      array_push($ret_array, $row["site"]);
    }
  }
  return json_encode($ret_array);
}

function get_columns($conn, $loc)
{
  $ret_array = Array();
  if(strlen($loc) > 1)
  {
    $new_loc = explode(" ", $loc);
    $location = $new_loc[0];
    for($j = 1; $j<sizeof($new_loc); $j++)
    {
      $location .= "_" . $new_loc[$j];
    }
  }
  $location .= "_peaks";
  $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'".$location."'";
  $result = $conn->query($sql);
  if($result->num_rows>0)
  {
    $i = 0;
    while($row = $result->fetch_assoc())
    {
      if($i>1)
        array_push($ret_array, $row['COLUMN_NAME']);
      $i++;
    }
  }
  return json_encode($ret_array);
}

//*******************************************************************************************************************************

//-----------------------------------------------------------------------------------------------------------------------------------------------

//*************************************DROP TABLE IF NECESSARY*******************************************************
function drop_table($conn, $table_name)
{
  $sql = "DROP TABLE " . $table_name;

  if($conn->query($sql) === TRUE)
  {
    echo "Table " . $table_name . " Dropped Successfully";
  }
  else
  {
    echo "Error dropping table " . $table_name . " : " . $conn->error;
  }
  $conn->commit();
}
//**************************************************************************************************************************


function close_connection($conn)
{
  $conn->close();
}

?>
