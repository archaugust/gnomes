$(function(){
	$('label').each(function(){
		if ($(this).find('span').length == 0)
			$(this).html($(this).html().replace('*', '<span class="u-blue">*</span>'));
	})

	$("input[name='billing']").change(function(){
		if ($(this).val() == 'Same') {
			$("#form_billing_first_name").val($("#form_first_name").val());
			$("#form_billing_last_name").val($("#form_last_name").val());
			$("#form_billing_phone").val($("#form_phone").val());
			$("#form_billing_email").val($("#form_email").val());
			$("#form_billing_address").val($("#form_address").val());
			$("#form_billing_city").val($("#form_city").val());
			$("#form_billing_country").val($("#form_country").val());
			$("#form_billing_region").val($("#form_region").val());
			$("#form_billing_postcode").val($("#form_postcode").val());
		}
		else {
			$("#form_billing_first_name").val('');
			$("#form_billing_last_name").val('');
			$("#form_billing_phone").val('');
			$("#form_billing_email").val('');
			$("#form_billing_address").val('');
			$("#form_billing_country").val('');
			$("#form_billing_region").val('');
			$("#form_billing_city").val('');
			$("#form_billing_postcode").val('');
		}
	});
});