<?php

namespace WPBrazilianRegistry\functions;

function is_rest_api () {
	return get_defined( 'REST_REQUEST' ) && REST_REQUEST;
}
