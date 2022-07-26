<?php
/**
 * Brazilian Fields Validation.
 *
 */

namespace WPBrazilianRegistry;

use WPBrazilianRegistry\Common\Hooker_Trait;

/**
 * Validations class.
 */
class Validations
{
	use Hooker_Trait;

	/**
	 * Checks if already exists CPF.
	 *
	 * @param string $cpf CPF to validate.
	 *
	 * @return bool
	 */
	public static function exists_cpf($cpf)
	{
		$exists_cpf = get_users([
			'meta_key' => 'billing_cpf',
			'meta_value' => $cpf
		]);

		return $exists_cpf;
	}

	/**
	 * Checks if already exists CNPJ.
	 *
	 * @param string $cnpj CNPJ to validate.
	 *
	 * @return bool
	 */
	public static function exists_cnpj($cnpj)
	{
		$exists_cnpj = get_users([
			'meta_key' => 'billing_cnpj',
			'meta_value' => $cnpj
		]);

		return $exists_cnpj;
	}
}
