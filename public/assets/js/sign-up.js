$(document).ready(function(){

	if (location.search == '?already_registered=email') {
		$('#duplicateMessage').html('Sorry, your email address has already been registered.');
		$('#modalEmailDuplication').modal();
	}

	if (location.search == '?already_registered=google') {
		$('#duplicateMessage').html('Sorry, your email address has already been registered through Google Social Login.');
		$('#modalEmailDuplication').modal();
	}

	if (location.search == '?already_registered=facebook') {
		$('#duplicateMessage').html('Sorry, your email address has already been registered through Facebook Social Login.');
		$('#modalEmailDuplication').modal();
	}

	$("#tandc").change(function() {
	    if ($("#tandc").is(':checked')) {
            $('#signup_button').prop('disabled', false);
	    }
	    else {
			$('#signup_button').prop('disabled', true);
		}
	});

	$("#name").change(function() {
		$("#name").removeClass("is-invalid");
		$('#name_error').addClass("d-none").hide();
		$('#verify_error').addClass("d-none").hide();
	});

	$("#email").change(function() {
		$("#email").removeClass("is-invalid");
		$('#email_error').addClass("d-none").hide();
		$('#verify_error').addClass("d-none").hide();
	});

	$("#password").change(function() {
		$("#password").removeClass("is-invalid");
		$('#password_error').addClass("d-none").hide();
		$('#verify_error').addClass("d-none").hide();
	});

	$("#name").focus();

});

function signup(channel) {

    $('#signup_cog').html('<i class="fa fa-spin fa-cog"></i>');
	$('#signup_button').prop('disabled', true);

	var base_url = $("#base_url").val();

	var error = false;

    if ($('#password').val() == '') {
    	$("#password").addClass("is-invalid");
    	$('#password').focus();
    	error = true;
    }
    else {
    	var passwordReg = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/;
    	var passwordValue = $("#password").val();
    	if (!passwordReg.test(passwordValue) || passwordValue.length == 0) {
        	$("#password").addClass("is-invalid");
        	$('#password_error').removeClass("d-none").show();
        	$('#password').focus();
        	error = true;
    	}
    }

    if ($('#email').val() == '') {
    	$("#email").addClass("is-invalid");
    	$('#email').focus();
    	error = true;
    }
    else {
		    	
    	$.ajax({
    	    type: 'POST',
    		url: base_url + '/sign-up/email-validation',
    		data: $('#email').val(),
    		success: function(result) {
                if (result.success === false) {
                  	$("#email").addClass("is-invalid");
                   	$('#email').focus();
                   	error = true;
                   	if (result.message == "user already registered") {
                    	$('#email_error').removeClass("d-none").show();
                   	}
                }
            },
    		async:false
        });
    }

    if ($('#name').val() == '') {
    	$("#name").addClass("is-invalid");
    	$('#name').focus();
    	error = true;
    }

    if (error === false) {

    	$.ajax({
    	    type: 'POST',
    		url: base_url + '/sign-up',
    		data: 'name=' + encodeURIComponent($('#name').val()) + '&email=' + encodeURIComponent($('#email').val()) + '&password=' + encodeURIComponent($('#password').val()),
    		success: function(result) {
    			$('#signup_cog').html('');
                if (result.success === true) {
					$('#name').val('');
					$('#name').focus();
					$('#email').val('');
					$('#password').val('');
					$('#tandc').prop('checked', false);
                	$('#success').removeClass("d-none").show();
                }
                else {
					$('#signup_button').prop('disabled', false);
				}
            },
    		async:true
        });

    }
    
    $('#signup_cog').html('');
    $('#signup_button').prop('disabled', false);
    
    return false;

}
