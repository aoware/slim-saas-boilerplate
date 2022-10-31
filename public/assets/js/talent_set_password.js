$(document).ready(function(){

	large_screen_layout();

	$("#password").change(function() {
		$("#password_label").removeClass("profile-builder-label-error");
		$('#password_error').addClass("d-none").hide();
	});

	$('#password').focus();

});

$(window).resize(function () {

	large_screen_layout();

});

function set() {

	var base_url = $("#base_url").val();

	var error = false;

    if ($('#password').val() == '') {
    	$("#password_label").addClass("profile-builder-label-error");
    	$('#password').focus();
    	error = true;
    }
    else {
    	var passwordReg = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/;
    	var passwordValue = $("#password").val();
    	if (!passwordReg.test(passwordValue) || passwordValue.length == 0) {
        	$("#password_label").addClass("profile-builder-label-error");
        	$('#password_error').removeClass("d-none").show();
        	$('#password').focus();
        	error = true;
    	}
    }

    if (error === false) {

		$('#set_cog').html('<i class="fa fa-spin fa-cog tdd-cog"></i>');
    	$('#set_button').prop('disabled', true);

    	$.ajax({
    	    type: 'POST',
    		url: base_url + '/set-password',
    		data: 'token=' + $('#token').val() + '&password=' + encodeURIComponent($('#password').val()),
    		success: function(result) {
                if (result.success === true) {
        			$('#set_cog').html('');
        			$('#set_button').prop('disabled', false);
                	$('#set_form').addClass("d-none").hide();
                	$('#set_thank_you').removeClass("d-none").show();
                }
                else {
                	window.location = base_url + '/travel-talent/reset-password?token_error=true';
                }
            },
    		async:true
        });
    }

}
