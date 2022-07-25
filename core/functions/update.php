<?php

namespace WPBrazilianRegistry\functions;

function save_fields($data, $customer_id)
{
	foreach($data as $k => $dataValue) {
		if(isset($dataValue)) {
			update_user_meta($customer_id, $k, sanitize_text_field($dataValue));
		}
	}
}
