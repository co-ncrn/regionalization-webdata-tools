
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

#### References

Command-Line Cartography, Mike Bostock
https://medium.com/@mbostock/command-line-cartography-part-1-897aa8f8ca2c