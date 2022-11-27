$(document).ready(function(){

	large_screen_layout();

	if (location.search == '?token_error=true') {
		$("#token_error").removeClass("d-none").show();
	}

	$("#email").change(function() {
		$("#email_label").removeClass("profile-builder-label-error");
	});

	$('#email').focus();

});

$(window).resize(function () {

	large_screen_layout();

});

function reset() {

	var error = false;

    if ($('#email').val() == '') {
    	$("#email_label").addClass("profile-builder-label-error");
    	$('#email').focus();
    	error = true;
    }
    else {

    	$.ajax({
    	    type: 'POST',
    		url: base_url + '/service/email',
    		data: $('#email').val(),
    		success: function(result) {
                if (result.success === false) {
                  	$("#email_label").addClass("profile-builder-label-error");
                   	$('#email').focus();
                   	error = true;
                }
            },
    		async:false
        });
    }

    if (error === false) {

		$('#reset_cog').html('<i class="fa fa-spin fa-cog"></i>');
    	$('#reset_button').prop('disabled', true);

    	$.ajax({
    	    type: 'POST',
    		url: base_url + '/reset-password',
    		data: 'email=' + encodeURIComponent($('#email').val()),
    		success: function(result) {
                if (result.success === true) {
        			$('#reset_cog').html('');
        			$('#reset_button').prop('disabled', false);
                	$('#reset_form').addClass("d-none").hide();
                	$('#reset_thank_you').removeClass("d-none").show();
                }
            },
    		async:true
        });
    }

}
