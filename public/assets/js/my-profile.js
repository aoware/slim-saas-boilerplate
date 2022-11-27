$(document).ready(function(){

	$("#first_name").focus();

});

function update(endPoint) {

	var error = false;

    if ($('#email').val() == '') {
    	$("#email").addClass("is-invalid");
    	$('#email').focus();
    	error = true;
    }

    if ($('#last_name').val() == '') {
    	$("#last_name").addClass("is-invalid");
    	$('#last_name').focus();
    	error = true;
    }

    if ($('#first_name').val() == '') {
    	$("#first_name").addClass("is-invalid");
    	$('#first_name').focus();
    	error = true;
    }

    if (error === false) {

		$('#action_cog').html('&nbsp;&nbsp;<i class="fa fa-spin fa-cog"></i>');
    	$('#actione_button').prop('disabled', true);

    	$.ajax({
    	    type: 'POST',
    		url: base_url + '/' + endPoint + '/my-profile',
    		data: $('#my_profile_form').serialize(),
    		success: function(result) {

    			$('#action_cog').html('');
    			$('#action_button').prop('disabled', false);

                if (result.success === false) {
                	$("#email").addClass("is-invalid");
                	$('#error_messages').text(result.message);
                	$('#errors').removeClass("d-none").show();
                }
                else {                	
                    alertify.success(result.message,2);
                }
            },
    		async:true
        });
    }

    return false;

}

function change_password(endPoint) {

    var confirm_content = $('#confirm_content').html();

    alertify.confirm(confirm_content,
        function(){ 
			$.ajax({
	    	    type: 'POST',
	    		url: base_url + '/' + endPoint + '/my-profile-password',
	    		data: $('#confirm_content').serialize(),
	    		success: function(result) {
	
	                if (result.success === false) {
						alertify.error(result.message,2);
	                }
	                else {
						alertify.success(result.message,2);	                	
	                }
	            },
	    		async:true
	        }); 
		}	
	).set('reverseButtons', true);
}