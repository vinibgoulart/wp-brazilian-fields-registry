<?php

return function ( $value, $args ) {
	return preg_replace( '/[^a-z-_]/i', '', $value );
};
