
### Tools for preparing regionalization data for the web

#### Initial scripts: Moving Files, importing data into MySQL

Import all CSVs from each of the ZIPs in Regionalization data into MySQL
```sh
./php-mysql-zip-import/insert.php
```

Import / store metadata
```sh
./php-mysql-zip-import/insert.php
```

Copy all the original geojson tracts into new folder
```sh
./php-geojson/geojson-copy.php
```



Remove unwanted geojson properties
```sh
./removeGeojsonProps/index.js // single-file node.js version
./php-geojson/geojson-clean.php // multi-file version
```

Geojson > Topojson conversation, simplication, and quantize
```sh
./php-geojson/geojson-to-topo.php
```
