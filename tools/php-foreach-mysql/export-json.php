<?php

/**
 *	Export all scenarios from MySQL as JSON 
 * 	Run on command line only
 */

require __DIR__ . '/vendor/autoload.php';	// libs
require 'inc/header.php';	 				// settings
require 'inc/config.php';	 				// create database connection


// testing
$ts = 0;
$cs = 0;
$limit = 20;





function returnAllTables(){
	global $db;
	return $db->rawQuery('SHOW TABLES;');
}
function returnAllColumns($table){
	global $db;
	return $db->rawQuery('SHOW COLUMNS FROM '.$table);
}
function returnAllTableCols($table){
	global $db;
	return $db->rawQuery('SHOW COLUMNS FROM '.$table);
}



// get all tables in the database
foreach (returnAllTables() as $t) {
    //print_r ($t);

    $table = $t['Tables_in_regionalization_full_slim'];

    // testing
	//if ($table != '10180_gen_input_tracts') continue;

	// just loop through tracts files
	if ( strpos($table, 'tracts') !== false) {

		$tableNameArr = explode("_", $table);
		$msa = $tableNameArr[0];
		$scenario = $tableNameArr[1];
		print $msa ." - ". $scenario ." - ". $table."\n";


		// get all data in the table
		$tableCols = returnAllTableCols($table);
		//print_r($tableCols);
		foreach ($tableCols as $col) {
			



			// if it is a column we want to edit
			if ( $col['Field'] != 'TID' && $col['Field'] != 'RID' && strpos($col['Field'], 'CV') !== false ) {
				$field = str_replace("CV", "", $col['Field']);
				print $field ."\n";


				$sql = "SELECT 
							t.TID, c.RID, t.".$field."E as tEst, r.".$field."E as rEst, 
							t.".$field."M as tMar, r.".$field."M as rMar, 
							t.".$field."CV as tCV, r.".$field."CV as rCV
						FROM ".$msa."_".$scenario."_input_tracts t, ".$msa."_".$scenario."_output_regions r, ".$msa."_".$scenario."_crosswalk c
						WHERE t.TID = c.TID AND r.RID = c.RID
						ORDER BY RID;";

				//print $sql;

				$data = $db->rawQuery($sql);
				$json = [];
				foreach ($data as $d) {
					//print_r ($d);
					
					// remove "g"
					$d['TID'] = str_replace("g","",$d['TID']);

					// create TRACT scale (a min / max for each TRACT)
					// this will be the scale for the axis as well so the change will be obvious
					$d['tMarMin'] = $d['tEst'] - $d['tMar'];
					$d['tMarMax'] = $d['tEst'] + $d['tMar'];

					// create REGION scale (a min / max for each REGION)
					$d['rMarMin'] = $d['rEst'] - $d['rMar'];
					$d['rMarMax'] = $d['rEst'] + $d['rMar'];


					$json[$d['TID']] = $d;
				}


				// write to file
				file_put_contents('../../data/scenarios/'.$msa.'_'.$scenario.'_'.$field.'.json', json_encode($json));
			}
			// testing
			//if (++$cs > $limit) break;

			print_r($json);
		}
	}
   
    // testing
	//if (++$ts > $limit) break;
}




die('all done');

?>