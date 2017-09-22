
### Tools for preparing regionalization data for the web


#### Required

PHP,node,npm
https://github.com/topojson


#### Initial scripts: Moving Files, importing data into MySQL

Import all CSVs from each of the ZIPs in Regionalization data into MySQL
```sh
$ php ./php-mysql-zip-import/insert.php
```

Import / store metadata
```sh
$ php ./php-mysql-zip-import/insert.php
```

Copy all the original geojson tracts into new folder
```sh
$ php ./php-geojson/geojson-copy.php
```

Round floats in all MySQL tables
```sh
$ php ./php-foreach-mysql/round-floats.php
```

Export all MySQL tables as JSON scenarios
```sh
$ php ./php-foreach-mysql/export-json.php
```




Remove unwanted geojson properties
```sh
$ node ./removeGeojsonProps/index.js // single-file node.js version
$ php ./php-geojson/geojson-clean.php // multi-file version
```

Geojson > Topojson conversion, simplication, and quantize
```sh
$ php ./php-geojson/geojson-to-topo.php convert-to-topo // convert to topojson
$ php ./php-geojson/geojson-to-topo.php simplify // 
$ php ./php-geojson/geojson-to-topo.php convert-to-topo
$ php ./php-geojson/geojson-to-topo.php convert-to-topo
```

