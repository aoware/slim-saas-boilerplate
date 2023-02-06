$(document).ready(function(){

	$("#code").focus();

});

function mfa_continue() {

	var error = false;

    if ($('#code').val() == '') {
    	$("#code").addClass("is-invalid");
    	$('#code').focus();
    	error = true;
    }

    if (error === false) {

		$('#continue_cog').html('&nbsp;&nbsp;<i class="fa fa-spin fa-cog"></i>');
    	$('#continue_button').prop('disabled', true);

    	$.ajax({
    	    type: 'POST',
    		url: base_url + '/mfa-code',
    		data: 'mfa_email=' + encodeURIComponent($('#mfa_email').val()) + '&mfa_password=' + encodeURIComponent($('#mfa_password').val()) + '&mfa_code=' + encodeURIComponent($('#code').val()),
    		success: function(result) {

    			$('#continue_cog').html('');
    			$('#continue_button').prop('disabled', false);

                if (result.success === false) {
					$('#verification_alert').addClass("d-none").hide();
                	$("#code").addClass("is-invalid");
                	$('#error_message').text(result.message);
                	$('#error').removeClass("d-none").show();
                	$("#code").focus();
                }
                else {
                	window.location = result.data.redirection_path;
                }

            },
    		async:true
        });
    }

    return false;

}