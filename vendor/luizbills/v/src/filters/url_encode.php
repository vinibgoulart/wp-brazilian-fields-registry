<?php

// encodes a URL
// see: https://www.php.net/manual/en/function.urlencode.php
return function ( $value, $args ) {
	return \urlencode( (string) $value );
};
