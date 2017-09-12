

Tools for preparing data for the web


# Initial scripts: Moving Files, importing data into MySQL

Import all the CSVs, from each of the ZIPs in Regionalization data, into MySQL
./php-mysql-zip-import/insert.php

Import / store metadata
./php-mysql-zip-import/insert.php

Copy all the original geojson tracts into new folder
./php-geojson/geojson-copy.php




Remove unwanted geojson properties
./removeGeojsonProps/index.js (single-file node.js version)
./php-geojson/geojson-clean.php (multi-file version)

Geojson > Topojson conversation, simplication, and quantize
./php-geojson/geojson-to-topo.php


