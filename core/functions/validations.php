<?php

namespace WPBrazilianRegistry\functions;

use Extra_Checkout_Fields_For_Brazil_Formatting;
use WPBrazilianRegistry\ValidateBrazilianFields;

function validate_fields($data, $errors)
{
	if ($data['billing_persontype'] === 'F') {
		validateCpf($data['billing_cpf'], $errors);
	} else if ($data['billing_persontype'] === 'J') {
		validateCnpj($data['billing_cnpj'], $errors);
		validateIe($data['billing_ie'], $errors);
	}
	return $errors;
}

function validateCpf($cpf, $errors)
{
	$cpf = preg_replace('/[^0-9]/', '', $cpf);

	if ((isset($cpf) && empty($cpf)) || !isset($cpf)) {
		$errors->add('billing_cpf_error', __('Insira um <strong>CPF</strong> válido', 'woocommerce'));
	} else {
		if (!ValidateBrazilianFields::is_cpf($cpf)) {
			$errors->add('billing_cpf_error', __('Insira um <strong>CPF</strong> válido', 'woocommerce'));
		}
	}

	return $errors;
}

function validateCnpj($cnpj, $errors)
{
	$cnpj = preg_replace('/[^0-9]/', '', $cnpj);

	if ((isset($cnpj) && empty($cnpj)) || !isset($cnpj)) {
		$errors->add('billing_cnpj_error', __('Insira um <strong>CNPJ</strong> válido', 'woocommerce'));
	} else {
		if (!ValidateBrazilianFields::is_cnpj($cnpj)) {
			$errors->add('billing_cnpj_error', __('Insira um <strong>CNPJ</strong> válido', 'woocommerce'));
		}
	}

	return $errors;
}

function validateIe($ie, $errors)
{
	if ((isset($ie) && empty($ie)) || !isset($ie)) {
		$errors->add('billing_ie_error', __('Insira uma <strong>Inscrição Estadual</strong> válida', 'woocommerce'));
	}
}
