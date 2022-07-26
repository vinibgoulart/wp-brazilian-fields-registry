<?php

// Remove <script> and <style> tags
return function ( $value, $args ) {
	return \strip_tags( \v( (string) $value, 'raw', 'safe_html' ) );
};
