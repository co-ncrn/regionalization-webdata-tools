<?php

/**
 *	Copy all the original geojson tracts into new folder
 */

require __DIR__ . '/vendor/autoload.php';	// libs
require 'inc/header.php';	 				// settings
require 'inc/config.php';	 				// create database connection


// testing
$dirtests = 0;
$inserttests = 0;
$limit = 2;

// get list of directories
$root = '/Users/owmundy/Sites/RegionalismMap/code/';
$path   = $root . 'beckymasond.github.io/msa_data/';
$cppath = $root . 'regionalization-webdata-tools/data/tracts/geojson/';
$dirs = scandir($path);
$filestr = "";

// reporting
$directory_count = $total_file_count = 0;
$geojson_file_count = $geojson_missing_count = 0;


// ACS scenarios
$scenarios = array(	"gen","hous","pov","trans");

// loop through directories
foreach ($dirs as $dir) {
	// exclude these directories
	if ($dir === '.' || $dir === '..' || $dir === '.DS_Store') continue;
	
	// count directories, sub-directories
	$directory_count++; 

	print ++$dirtests .". ". $dir ."/ \n";

	// geojson file path
	$geojson = $dir ."_tract.geojson";

	// make sure file exists
	if (!file_exists($path.$dir."/".$geojson)) {
		print "\t - ". $geojson ." - ####### geojson FILE MISSING ########"."\n";
		$geojson_missing_count++;
	} else {
		$geojson_file_count++;

		// get string
		$filestr = file_get_contents($path.$dir."/".$geojson);

		// here I found these were ASCII, so the following code
		// confirm with: $ file -I <file>

		// convert to UTF8
		$filestr = mb_convert_encoding($filestr, "UTF-8");

		// double-sure
		iconv(mb_detect_encoding($filestr, mb_detect_order(), true), "UTF-8", $filestr);

		// save contents
		file_put_contents($cppath.$geojson, $filestr);

		// copy file to new location
		// this is faster but not guaranteed you'll get the right encoding
		//copy($path.$dir."/".$geojson, $cppath.$geojson);


	}
}





// reporting
print $directory_count ." directory_count\n";
print $geojson_file_count ." geojson files (". $geojson_missing_count ." missing)\n";
print $total_file_count ." total files\n";








?>