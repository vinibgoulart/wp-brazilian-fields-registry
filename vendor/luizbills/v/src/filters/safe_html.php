<?php

// Remove <script> and <style> tags
return function ( $value, $args ) {
	// remove all script and style tags with code
	$value = \preg_replace( '/<(script|style)[^>]*?>.*?<\/\\1>/si', '', $value );
	// remove any script, style, link and iframe tags
	$value = \preg_replace( '/<(script|style|iframe|link)[^>]*?>/si', '', $value );

	return $value;
};
