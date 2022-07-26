<?php

// Remove all non-numeric chars, but leave white spaces
return function ( $value, $args ) {
	return preg_replace( '/[^0-9\s]/', '', $value );
};
