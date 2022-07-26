<?php

// Trim whitespace from the beginning and end of a string
return function ( $value, $args ) {
	return \trim( (string) $value );
};
