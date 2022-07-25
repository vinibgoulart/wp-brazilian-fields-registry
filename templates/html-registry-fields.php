<?php

/**
 * Fields will be synchronized with woocommerce customer
 *
 */

?>
<p class="form-row">
	<label for="reg_billing_first_name"><?php _e('Primeiro nome', 'woocommerce'); ?><span
			class="required"> *</span></label>
	<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name"
		   value="<?php if (!empty($_POST['billing_first_name'])) esc_attr_e($_POST['billing_first_name']); ?>"/>
</p>
<p class="form-row">
	<label for="reg_billing_last_name"><?php _e('Último nome', 'woocommerce'); ?><span
			class="required"> *</span></label>
	<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name"
		   value="<?php if (!empty($_POST['billing_last_name'])) esc_attr_e($_POST['billing_last_name']); ?>"/>
</p>
<p class="form-row">
	<label for="reg_billing_phone"><?php _e('Telefone', 'woocommerce'); ?><span class="required"> *</span></label>
	<input type="text" class="input-text phone" name="billing_phone" id="reg_billing_phone"
		   value="<?php esc_attr_e($_POST['billing_phone']); ?>"/>
</p>
<p class="form-row">
	<label for="reg_billing_persontype"><?php _e('Tipo de Pessoa', 'woocommerce'); ?><span class="required"> *</span></label>
	<select name="billing_persontype" id="reg_billing_persontype">
		<option value="F">Pessoa Física</option>
		<option value="J">Pessoa Jurídica</option>
	</select>
</p>
<!--
	TODO: remove mask form fields plugin dependency
	class like "cpf" and "cnpj" create a mask in field
-->
<p class="form-row field-pessoa-fisica">
	<label for="reg_billing_cpf"><?php _e('CPF', 'woocommerce'); ?><span class="required"> *</span></label>
	<input type="text" class="input-text cpf" name="billing_cpf" id="reg_billing_cpf"
		   value="<?php esc_attr_e($_POST['billing_cpf']); ?>"/>
</p>
<p class="form-row field-pessoa-juridica" style="display: none">
	<label for="reg_billing_cnpj"><?php _e('CNPJ', 'woocommerce'); ?><span class="required"> *</span></label>
	<input type="text" class="input-text cnpj" name="billing_cnpj" id="reg_billing_cnpj"
		   value="<?php esc_attr_e($_POST['billing_cnpj']); ?>"/>
</p>
<p class="form-row field-pessoa-juridica" style="display: none">
	<label for="reg_billing_ie"><?php _e('Inscrição Estadual', 'woocommerce'); ?><span class="required"> *</span></label>
	<input type="text" class="input-text ie" name="billing_ie" id="reg_billing_ie"
		   value="<?php esc_attr_e($_POST['billing_ie']); ?>"/>
</p>
<div class="clear"></div>

<script>
	let personTypeSelected = document.querySelector('#reg_billing_persontype')
	personTypeSelected.addEventListener('change', function () {
		if (this.value === 'F') {
			$(".field-pessoa-fisica").css("display", "block")
			$(".field-pessoa-juridica").css("display", "none")
		}
		if (this.value === 'J') {
			$(".field-pessoa-fisica").css("display", "none")
			$(".field-pessoa-juridica").css("display", "block")
		}
	})
</script>
