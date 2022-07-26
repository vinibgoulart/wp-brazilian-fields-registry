<?php

return function ( $value, $args ) {
	return $value . $args->get( 0 );
};
