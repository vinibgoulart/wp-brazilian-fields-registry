<?php

use WPBrazilianRegistry\functions as h;

$dir = __DIR__ . '/functions';

// load the 'rscandir' function first
include_once "$dir/file.php";

foreach ( h\rscandir( $dir ) as $file ) {
	include_once $file;
}

// load functions.php
$custom_functions = __DIR__ . '/../functions.php';

if ( file_exists( $custom_functions ) ) {
	include_once $custom_functions;
}
