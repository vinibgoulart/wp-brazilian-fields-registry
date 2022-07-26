<?php

return function ( $value, $args ) {
	return preg_replace( '/[^a-z\s]/i', '', $value );
};
