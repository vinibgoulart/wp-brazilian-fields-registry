<?php

return function ( $value, $args ) {
	return preg_replace( '/[^a-z0-9]/i', '', $value );
};
