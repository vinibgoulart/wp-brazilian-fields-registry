<?php

// Remove all non numeric chars
return function ( $value, $args ) {
	return preg_replace( '/[^0-9]/', '', $value );
};
