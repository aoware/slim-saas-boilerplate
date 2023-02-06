$(document).ready(function(){

	$("#email").focus();

	$("#password").on('keypress',function(e) {
	    if (e.which == 13) {
	    	login("email");
	    }
	});

});

function login(channel) {

	var error = false;

    if ($('#password').val() == '') {
    	$("#password").addClass("is-invalid");
    	$('#password').focus();
    	error = true;
    }

    if ($('#email').val() == '') {
    	$("#email").addClass("is-invalid");
    	$('#email').focus();
    	error = true;
    }

    if (error === false) {

		$('#login_cog').html('&nbsp;&nbsp;<i class="fa fa-spin fa-cog"></i>');
    	$('#login_button').prop('disabled', true);

    	$.ajax({
    	    type: 'POST',
    		url: base_url + '/log-in/email',
    		data: 'login_email=' + encodeURIComponent($('#email').val()) + '&login_password=' + encodeURIComponent($('#password').val()),
    		success: function(result) {

    			$('#login_cog').html('');
    			$('#login_button').prop('disabled', false);

                if (result.success === false) {
					$('#verification_alert').addClass("d-none").hide();
                	$("#email").addClass("is-invalid");
                	$("#password").addClass("is-invalid");
                	$('#error_message').text(result.message);
                	$('#error').removeClass("d-none").show();
                	$("#email").focus();
                }
                else {
					if (result.data.action == 'redirection') {
                	    window.location = result.data.redirection_path;
                	}
					if (result.data.action == 'mfa') {
						$('#mfa_email').val($('#email').val());
						$('#mfa_password').val($('#password').val());
						$('#mfa_form').submit();
                	}
                }

            },
    		async:true
        });
    }

    return false;

}