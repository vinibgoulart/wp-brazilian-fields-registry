<?php

namespace WPBrazilianRegistry\functions;

use WPBrazilianRegistry\Brazilian_Fields_Formatting;

function validate_fields($data, $errors)
{
	if ($data['billing_persontype'] === 'F') {
		validate_cpf($data['billing_cpf'], $errors);
	} else if ($data['billing_persontype'] === 'J') {
		validate_cnpj($data['billing_cnpj'], $errors);
		validate_ie($data['billing_ie'], $errors);
	}
	return $errors;
}

function validate_cpf($cpf, $errors)
{
	$cpf = preg_replace('/[^0-9]/', '', $cpf);

	if ((isset($cpf) && empty($cpf)) || !isset($cpf)) {
		$errors->add('billing_cpf_error', __('Insira um <strong>CPF</strong> válido', 'woocommerce'));
	} else {
		if (!Brazilian_Fields_Formatting::is_cpf($cpf)) {
			$errors->add('billing_cpf_error', __('Insira um <strong>CPF</strong> válido', 'woocommerce'));
		}
	}

	return $errors;
}

function validate_cnpj($cnpj, $errors)
{
	$cnpj = preg_replace('/[^0-9]/', '', $cnpj);

	if ((isset($cnpj) && empty($cnpj)) || !isset($cnpj)) {
		$errors->add('billing_cnpj_error', __('Insira um <strong>CNPJ</strong> válido', 'woocommerce'));
	} else {
		if (!Brazilian_Fields_Formatting::is_cnpj($cnpj)) {
			$errors->add('billing_cnpj_error', __('Insira um <strong>CNPJ</strong> válido', 'woocommerce'));
		}
	}

	return $errors;
}

function validate_ie($ie, $errors)
{
	if ((isset($ie) && empty($ie)) || !isset($ie)) {
		$errors->add('billing_ie_error', __('Insira uma <strong>Inscrição Estadual</strong> válida', 'woocommerce'));
	}
}
