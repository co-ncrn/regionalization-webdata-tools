<?php

/**
 *	Remove unwanted geojson feature properties
 */

require __DIR__ . '/vendor/autoload.php';	// libs
require 'inc/header.php';	 				// settings
require 'inc/config.php';	 				// create database connection

$remove = array(
	// remove appearance information
	"fill","fill-opacity","stroke","stroke-opacity","stroke-width",
	// remove extra codes https://www2.census.gov/geo/docs/maps-data/data/tiger/prejoined/ACSMetadata2011.txt
	"STATEFP","COUNTYFP","TRACTCE","MTFCC","NAMELSAD","FUNCSTAT",
	"INTPTLAT","INTPTLON","NAME","RID","TID","code",

	// gen
	"65overCV","65overE","65overM",
	"avghhincCV","avghhincE","avghhincM",
	"avgroomsCV","avgroomsM","avgroomsE",
	"bachdegCV","bachdegE","bachdegM",
	"blackCV","blackE","blackM",
	"hispCV","hispE","hispM",
	"marriedCV","marriedE","marriedM",
	"occupiedCV","occupiedE","occupiedM",
	"pphhCV","pphhE","pphhM",
	"samehousCV","samehousE","samehousM",
	"under18CV","under18E","under18M",
	"whiteCV","whiteE","whiteM",
	//,"avgroomsE"

	// house
	"occupiedCV","pctownCV","pctrentCV","snglfmlyCV","avgroomsCV","avghmvalCV","avgrentCV",
	"occupiedE","pctownE","pctrentE","snglfmlyE","avgroomsE","avghmvalE","avgrentE",
	"occupiedM","pctownM","pctrentM","snglfmlyM","avgroomsM","avghmvalM","avgrentM",

	// pov
	"chabvpovCV","abvpovCV","employedCV","hsincownCV","hsincrentCV",
	"chabvpovE","abvpovE","employedE","hsincownE","hsincrentE",
	"chabvpovM","abvpovM","employedM","hsincownM","hsincrentM",

	// trans
	"drvloneCV","transitCV","vehiclppCV","avgcmmteCV",
	"drvloneE","transitE","vehiclppE","avgcmmteE",
	"drvloneM","transitM","vehiclppM","avgcmmteM"
);

// testing
$dirtests = 0;
$inserttests = 0;
$limit = 2;

// get list of files
$root = '/Users/owmundy/Sites/RegionalismMap/code/';
$path1   = $root . 'regionalization-webdata/data/tracts/geojson/';
$path2 = $root . 'regionalization-webdata/data/tracts/geojson_noprops/';
$files = scandir($path1);
$filestr = "";

// reporting
$directory_count = $total_file_count = 0;
$geojson_file_count = $geojson_missing_count = 0;
$props_removed_count = $features_count = 0;

// ACS scenarios
$scenarios = array(	"gen","hous","pov","trans");

// loop through directories
foreach ($files as $file) {
	// exclude these directories
	if ($file === '.' || $file === '..' || $file === '.DS_Store') continue;
	
	// count directories, sub-directories
	$directory_count++; 

	print ++$dirtests .". ". $file ." \n";





	// make sure file exists
	if (!file_exists($path1.$file)) {
		print "\t - ". $file ." - ####### geojson FILE MISSING ########"."\n";
		$geojson_missing_count++;
	} else {
		$geojson_file_count++;

		// get string
		$filestr = file_get_contents($path1.$file);

		// here I found these were ASCII, so the following code
		// confirm with: $ file -I <file>

		// convert to UTF8
		$filestr = mb_convert_encoding($filestr, "UTF-8");

		// double-sure
		iconv(mb_detect_encoding($filestr, mb_detect_order(), true), "UTF-8", $filestr);

		// remove unwanted props
		$filestr = removeProps($filestr);

		// save contents
		file_put_contents($path2.$file, $filestr);
	}

	//break; // testing
}


/**
 *	Removes props from feature
 */
function removeProps($str){
	global $remove, $features_count, $props_removed_count;

	$arr = json_decode($str, true);

	foreach ($arr["features"] as $key => $feature) {
		//print_r($feature["properties"]["TID"] ."\n");
		$features_count++;

		// move some vars to different places
		$arr["features"][$key]["TID"] = $feature["properties"]["TID"]; // set the TID in the feature
		$arr["features"][$key]["properties"]["lat"] = $feature["properties"]["INTPTLAT"]; // shorten lat name
		$arr["features"][$key]["properties"]["lng"] = $feature["properties"]["INTPTLON"]; // shorten lng name
		// confirm
		//print_r($arr["features"][$key]["properties"]["lat"] ."\n");
		//print_r($feature["properties"]["TID"] ."\n");
		//print_r($arr["features"][$key]["TID"] ."\n");


		// delete unwanted properties
		foreach ($feature["properties"] as $prop => $val) {
			//print_r("$prop => $val\n");

			$unset = false;

			// unset all props in $remove array
			if ( in_array($prop,$remove) ) $unset = true;
			// unset anything that starts with B
			if ( $prop[0] == "B" ) $unset = true;

			if ($unset == true){
				print_r("$prop => $val --- WILL BE REMOVED\n");
				unset($arr["features"][$key]["properties"][$prop]);
				$props_removed_count++;
			}

		}
	}
	//print_r($arr);
	return json_encode($arr, JSON_UNESCAPED_SLASHES);
}





// reporting
print $props_removed_count ." props_removed_count\n";
print $features_count ." features_count\n";
print $props_removed_count / $features_count  ." props_removed_count / features_count\n";
print $geojson_file_count ." geojson files (". $geojson_missing_count ." missing)\n";
print $total_file_count ." total files\n";








?>