


/**
 *	Remove unwanted geojson feature properties
 */

const fs = require('fs');
var inputFile, outputFile;

/**
 *	A function that will run on every property - Stores TID, lat, lng
 */
function editFunct(feature){
	feature.TID = feature.properties.TID;					// set the TID in the feature
	feature.properties.lat = feature.properties.INTPTLAT;	// shorten lat name
	feature.properties.lng = feature.properties.INTPTLON;	// shorten lng name
	return feature;
}
inputFile = '../data/geojson/16740_tract.geojson',
outputFile = '../data/geojson/16740_tract_clean.geojson';



var remove = [
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
];



// ALL FILES
inputFile  = "../../data/tracts/geojson_noprops/10180_tract.geojson";
outputFile = "../../data/tracts/geojson_noprops/10180_tract_clean.geojson";

 




/*
// MSA LAYER
function editFunct(feature){
	feature.properties.lat = feature.properties.INTPTLAT;	// shorten lat name
	feature.properties.lng = feature.properties.INTPTLON;	// shorten lng name
	return feature;
}
inputFile = "../data/geojson/msa_2013_qgis.geojson";
outputFile = "../data/geojson/msa_2013_qgis_remover.geojson";
remove = ["CSAFP","CBSAFP","NAMELSAD","LSAD","MEMI","MTFCC","ALAND","AWATER",
		  "GISJOIN","SHAPE_AREA","SHAPE_LEN","INTPTLAT","INTPTLON"];
*/









/**
 *	Remove unwanted geojson feature properties
 */
function removeGeojsonProps(inputFile,outputFile,remove,editFunct){

	console.log("1 removeGeojsonProps() called");
	// import geojson
	var file = fs.readFileSync(inputFile, 'utf8');
	//console.log(JSON.stringify(file));
	// parse
	//var geojson = JSON.parse(JSON.stringify(file));
	var geojson = JSON.parse(file.toString('utf8').replace(/^\uFEFF/, ''));
	// for each feature in geojson
	geojson.features.forEach(function(feature,i){
		// edit any properties
		feature = editFunct(feature);
		//console.log(feature);
		// remove any you don't want
		for (var key in feature.properties) {	
			// remove unwanted properties
			if ( key.charAt(0) == "B" || remove.indexOf(key) !== -1 ){
				delete feature.properties[key];
			}
		}
		console.log(feature);
		//console.log(feature.geometry.coordinates[0]);
	});
	console.log(outputFile,JSON.stringify(geojson));

	// write file
	fs.writeFile(outputFile, JSON.stringify(geojson), function(err) {
	    if(err) return console.log(err);
	    console.log("The file was saved!");
	}); 
}
//removeGeojsonProps(inputFile,outputFile,remove,editFunct);


/**
 *	Loop through all files in a directory and call the above function - NOT FINISHED
 */
function removeGeojsonPropsInDir(dir,remove,editFunct){
	// read directory
	fs.readdir(dir, (err, files) => {
		// the loop
		files.forEach(inputFile => {
			// if it's not a .geojson file
			if (inputFile.indexOf(".geojson") === -1) return;
			// if it hasn't already been cleaned
			if (inputFile.indexOf("_clean") !== -1) return;
			// full path to input file
			inputFile = dir + inputFile;
			// test
			//inputFile = "/Users/owmundy/Sites/RegionalismMap/code/regionalization-webdata-tools/data/tracts/geojson/10180_tract.geojson";
			// full path to output file
			var outputFile = inputFile.replace(".geojson","_clean.geojson");
			//outputFile = inputFile; // save over original
			console.log(inputFile,outputFile);
			removeGeojsonProps(inputFile,outputFile,remove,editFunct);
		});
	})
}
removeGeojsonPropsInDir('../../data/tracts/geojson_noprops/',remove,editFunct)





