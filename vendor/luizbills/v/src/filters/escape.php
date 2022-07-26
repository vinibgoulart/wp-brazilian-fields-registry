<?php

// Escape $value
return function ( $value, $args ) {
	return \htmlspecialchars( (string) $value, \ENT_QUOTES, 'UTF-8' );
};
