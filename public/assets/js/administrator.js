$(document).ready(function(){

	$("#first_name").focus();

});

function action(endPoint) {

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
    		url: base_url + '/backoffice/administrator/' + endPoint,
    		data: $('#administrator_form').serialize(),
    		success: function(result) {

    			$('#action_cog').html('');
    			$('#action_button').prop('disabled', false);

                if (result.success === false) {
                	$("#email").addClass("is-invalid");
                	$('#error_messages').text(result.message);
                	$('#errors').removeClass("d-none").show();
                }
                else {                	
				    alertify.alert(result.message,
				        function(){ 
							window.location = result.data.redirection; 
						}
					);
                }

            },
    		async:true
        });
    }

    return false;

}