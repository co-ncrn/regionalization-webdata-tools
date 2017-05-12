<?php

/**
 *	Remove unwanted geojson properties
 */

require __DIR__ . '/vendor/autoload.php';	// libs
require 'inc/header.php';	 				// settings
require 'inc/config.php';	 				// create database connection

// testing
$dirtests = 0;
$inserttests = 0;
$limit = 2;

// get list of files
$root = '/Users/owmundy/Sites/RegionalismMap/code/';
$path1 = $root . 'regionalization-webdata/data/tracts/geojson_noprops/';
$path2 = $root . 'regionalization-webdata/data/tracts/topojson/';
$files = scandir($path1);
$filestr = "";

// reporting
$directory_count = $total_file_count = 0;
$geojson_file_count = $geojson_missing_count = 0;
$props_removed_count = $features_count = 0;


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


		$topojsonfile = str_replace(".geojson", ".topojson", $file);

		$cli = "geo2topo tracts=". $path1.$file ." > ". $path2.$topojsonfile;

		exec($cli);

	}

	//break; // testing
}






// reporting
print $geojson_file_count ." geojson files (". $geojson_missing_count ." missing)\n";
print $total_file_count ." total files\n";








?>