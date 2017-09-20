<?php

/**
 *	Loop through all DB tables, reformatting numbers per rules below
 * 	Run on command line only, make sure MySQL / PHP have plenty of memory, and give it >2 hours on localhost
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
function returnAllTableData($table){
	global $db;
	return $db->rawQuery('SELECT * FROM '.$table);
}



// get all tables in the database
foreach (returnAllTables() as $t) {
    //print_r ($t);

    $table = $t['Tables_in_regionalization_full_slim'];
    $msa = substr($table, 0, 5);

    // testing
	//if ($table != '10180_gen_input_tracts_copy') continue;

    // if it is a table we want to use
    if ( is_numeric($msa) ){
    	//print $msa ." - ". $table."\n";

    	// if table name contains "tracts" or "regions"
		if ( strpos($table, 'tracts') !== false || strpos($table, 'regions') !== false) {
    		print $msa ." - ". $table."\n";

    		// get all data in the table
    		$tableData = returnAllTableData($table);
    		foreach ($tableData as $rowNum => $row) {

    			// loop through each row
    			foreach ($row as $key => $val) {
    				
    				// if it is a column we want to edit
					if ( $key != 'TID' && $key != 'RID') {
	    				
	    				print $key . " => " . $val;

	    				// round ints / floats based on following
	    					 if ($val > 1000) { $val = round($val); }
						else if ($val > 100) { 	$val = round($val); }
						else if ($val > 10) { 	$val = round($val,1); }
						else if ($val > 1) { 	$val = round($val,2); }
						else if ($val > .1) { 	$val = round($val,3); }
						else if ($val > .01) { 	$val = round($val,4); }
						else if ($val > .001) { $val = round($val,4); } 
						else if ($val > .0001) {$val = round($val,5); } 
						else if ($val > .00001) {$val = round($val,6); } 
						else if ($val > .000001) {$val = round($val,8); } 
						else if ($val > .0000001) {$val = round($val,9); } 
						else if ($val > .00000001) {$val = round($val,10); } 
						else if ($val > .000000001) {$val = round($val,11); } 
						else if ($val > .0000000001) {$val = round($val,12); } 
						else { 						   $val = round($val,13); }

						print " ========> " . $val ."\n";

						// set val back into row
						$row[$key] = $val;
					}

					// if tracts table
					if ( strpos($table, 'tracts') !== false){
						$db->where ('TID', $row['TID']);
					}
					// if regions table
					else if (strpos($table, 'regions') !== false) {
						$db->where ('RID', $row['RID']);
					}

					// put row back in table
					if ($db->update ($table, $row))
					    echo $db->count . ' records were updated';
					else
					    echo 'update failed: ' . $db->getLastError();


    			}
    			// testing
				//if (++$cs > $limit) break;
    		}
		}
    }
    // testing
	//if (++$ts > $limit) break;
}




die('all done');

?>