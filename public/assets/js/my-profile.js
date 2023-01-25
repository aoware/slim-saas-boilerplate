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

    var confirm_content  = "<form id'confirm_content'>";
        confirm_content += "  <div class='form-group mb-3 row'>";
        confirm_content += "    <label class='col-5 col-form-label'>Old Password</label>";
        confirm_content += "    <div class='col'>";
        confirm_content += "      <input type='password' class='form-control' name='old_password' onblur='copy_old_password(this.value)'>";
        confirm_content += "    </div>";
        confirm_content += "  </div>";
        confirm_content += "  <div class='form-group mb-3 row'>";
        confirm_content += "    <label class='col-5 col-form-label'>New Password</label>";
        confirm_content += "    <div class='col'>";
        confirm_content += "      <input type='password' class='form-control' name='new_password' onblur='copy_new_password(this.value)'>";
        confirm_content += "    </div>";
        confirm_content += "  </div>";                                      
        confirm_content += "  <div class='form-group mb-3 row'>";
        confirm_content += "    <label class='col-5 col-form-label'>Confirm New Password</label>";
        confirm_content += "    <div class='col'>";
        confirm_content += "      <input type='password' class='form-control' name='confirmed_password' onblur='copy_confirmed_password(this.value)'>";
        confirm_content += "    </div>";
        confirm_content += "  </div>";
        confirm_content += "</form>";        

    alertify.confirm(confirm_content,
        function(){ 
			$.ajax({
	    	    type: 'POST',
	    		url: base_url + '/' + endPoint + '/my-profile-password',
	    		data: 'old_password=' + $('#old_password').val() + '&new_password=' + $('#new_password').val() + '&confirmed_password=' + $('#confirmed_password').val(),
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

function copy_old_password(value) {
    $('#old_password').val(value);
}

function copy_new_password(value) {
    $('#new_password').val(value);
}

function copy_confirmed_password(value) {
    $('#confirmed_password').val(value);
}