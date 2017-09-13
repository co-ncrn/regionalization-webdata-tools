<?php

/**
 *	Geojson > Topojson conversion, simplication, and quantize
 *	Executes command line on entire directory of geojson/topojson files
 */

require __DIR__ . '/vendor/autoload.php';	// libs
require 'inc/header.php';	 				// settings
require 'inc/config.php';	 				// create database connection

// testing
$dirtests = 0;
$inserttests = 0;
$limit = 2;

// get list of files
$root = '/Users/owmundy/Sites/RegionalismMap/code/regionalization-webdata-tools/data/tracts/';

// step 1 - create topojson from geojson
if ($argv[1] == 'convert-to-topo'){
	$path1 = $root . 'geojson_noprops/';
	$path2 = $root . 'topojson/';
}
// the other steps (to decrease size)

// simplify topojson 
else if ($argv[1] == 'simplify'){
	$path1 = $root . 'topojson/';
	$path2 = $root . 'topojson_simplified/';
}
// quantize 1e5 topojson 
else if ($argv[1] == 'quantize-1e5'){
	$path1 = $root . 'topojson/';
	$path2 = $root . 'topojson_quantized_1e5/';
}
// quantize 1e6 topojson 
else if ($argv[1] == 'quantize-1e6'){
	$path1 = $root . 'topojson/';
	$path2 = $root . 'topojson_quantized_1e6/';
}


$files = scandir($path1);
$filestr = "";

// reporting
$directory_count = $total_file_count = 0;
$geojson_file_count = $geojson_missing_count = 0;


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

		
		// create topojson from geojson
		// $ geo2topo tracts=16740_tract2.geojson > 16740_tract3.topojson
		if ($argv[1] == 'convert-to-topo'){
			$topojsonfile = str_replace(".geojson", ".topojson", $file);
			$cli = "geo2topo tracts=". $path1.$file ." > ". $path2.$topojsonfile;
		}
		// simplify topojson 
		// $ toposimplify -p 1 -f < 16740_tract2.topojson > 16740_tract2-simple.topojson
		else if ($argv[1] == 'simplify'){
			$cli = "toposimplify -p 1 -f < ". $path1.$file ." > ". $path2.$file;
		}
		// quantize 1e5 topojson 
		// $ topoquantize 1e5 < 16740_tract2-simple.topojson > 16740_tract2-simple-quantized.topojson
		else if ($argv[1] == 'quantize-1e5'){
			$cli = "topoquantize 1e5 < ". $path1.$file ." > ". $path2.$file;
		}
		// quantize 1e6 topojson 
		// $ topoquantize 1e6 < 16740_tract2-simple.topojson > 16740_tract2-simple-quantized.topojson
		else if ($argv[1] == 'quantize-1e6'){
			$cli = "topoquantize 1e6 < ". $path1.$file ." > ". $path2.$file;
		}



		exec($cli); // execute the command line
	}
	//break; // testing
}


// reporting
print $geojson_file_count ." geojson files (". $geojson_missing_count ." missing)\n";
print $total_file_count ." total files\n";


?>