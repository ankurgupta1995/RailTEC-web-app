<?php


//*************************FILE PARSER FUNCTION***************************************
function parser($lines)
{
	//$lines = file($filename, FILE_IGNORE_NEW_LINES);

	$flag = 0;
	$peaks_array = array();
	$peakloc_array = array();

	foreach($lines as $key => $value)
	{
		//Newline character messes stuff up.
		if(strpos($value, "Site,") !== False)
		{
			$site = $lines[$key + 1];
			//echo $site;
		}
		else if(strpos($value, "Year") !== False)
		{
			$date_time = $lines[$key + 1];
			//echo $date_time;
		}
		else if (strpos($value, "Speed") !== False)
		{
			$new_var = explode(",", $lines[$key + 1]);
			$speed = $new_var[0];
			$direction = $new_var[1];
			$columns = $lines[$key + 2];
			//echo $speed;
		}
		else if (strpos($value, "Fault") !== False)
		{
			$fault = $lines[$key + 1];
			//echo $fault;
		}
		else if (strpos($value, "Temperature") !== False)
		{
			$new_var = explode(",", $lines[$key+1]);
			$top_temp = $new_var[0];
			$base_temp = $new_var[1];
			$ambient_temp = $new_var[2];
		}
		else if ((strpos($value, "Peaks") !== False) || $flag == 1)
		{
			if($flag == 0)
			{
				$flag = 1;
				continue;
			}
			if(!(strpos(($lines[$key+1]), "Gauge Peak Locations,") !== False))
			{
				array_push($peaks_array, $value);
			}
			else
			{
				$flag = 2;
				//print_r($peaks_array);
				continue;
			}
		}
		else if ($flag == 2)
		{

			if($flag == 0)
			{
				$flag = 2;
				continue;
			}

			if($key !== count($lines) - 3)
			{
				array_push($peakloc_array, $value);
			}
			
			if ($key == count($lines) - 3)
			{
				array_splice($peakloc_array, 0, 1);
				//print_r($peakloc_array);
			}
		}
	}

	//remove commas from end of everything

	return remove_commas(array('site' => $site, 'direction' => $direction, 'date_time' => $date_time, 'speed' => $speed, 'columns' => $columns, 'fault' => $fault, 'top_temp' => $top_temp, 'base_temp' => $base_temp, 'ambient_temp' =>$ambient_temp, 'peaks' => $peaks_array, 'peaks_loc' => $peakloc_array));
}


function remove_commas($array)
{
	$new_array = array();
	foreach($array as $key => $val)
	{
		if($key === 'peaks' || $key === 'peaks_loc')
		{
			foreach($array[$key] as $idx => $data)
			{
				$new_array[$key][$idx] = substr($data, 0, -2);
			}
		}
		else if($key === 'columns')
		{
			$new_array[$key] = substr($val, 0, -1);
		}
		else if(!($key === 'speed' || $key === 'direction' || $key === 'top_temp' || $key === 'base_temp' || $key === 'ambient_temp'))
		{
			$new_array[$key] = substr($val, 0, -2);
		}
		else
		{
			$new_array[$key] = $val;
		}
	}
	if(sizeof($new_array['peaks_loc']) !== sizeof($new_array['peaks']))
	{
		unset($new_array['peaks_loc'][12]);
	}
	return $new_array;
}


//**********************************************************************************

?>