$(document).ready(function(){

	$("#first_name").focus();

    var elem = document.querySelector('#user_2fa');
    var switchery = new Switchery(elem,  { color : '#206bc4', size: 'small' });

    var u2fa = document.querySelector('#user_2fa');
    u2fa.onchange = function() {
       $("#hidden_2fa").val(0);
       display_2fa();
    };

});

function update(end_point) {

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
    		url: base_url + '/' + end_point + '/my-profile',
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
                    alertify.success(result.message,3);
                }
            },
    		async:true
        });
    }

    return false;

}

function change_password(end_point) {

    var confirm_content  = "<form id='confirm_content'>";
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
	    		url: base_url + '/' + end_point + '/my-profile-password',
	    		data: 'old_password=' + $('#old_password').val() + '&new_password=' + $('#new_password').val() + '&confirmed_password=' + $('#confirmed_password').val(),
	    		success: function(result) {

	                if (result.success === false) {
						alertify.error(result.message,3);
	                }
	                else {
						alertify.success(result.message,3);
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

function display_2fa() {

	if ($("#user_2fa").is(':checked')) {

    	var modal_body = "<h4>Two-Factor Authentication</h4>";
    	modal_body    += "<p>Please use your Authenticator app (such as Google Authenticator, Twilio Authy or DUO) to scan this QR code.</p>";
    	modal_body    += "<table><tr style='vertical-align:top'>";
    	modal_body    += "<td width='50%'><img src='" + $("#2fa_url_qr").val() + "' /></td>";
    	modal_body    += "<td width='50%'><br /><br /><br />Please Scan the QR code into your Authenticator app and capture the code displayed to validate.";
    	modal_body    += "<br /><br /><br />Code: <input type='text' name='2fa_code' id='2fa_code'>";
    	modal_body    += "<br /><br /><br /><button class='btn btn-primary btn-sm' type='button' onclick='update_2fa(\"" + $("#user_area").val() + "\")'>Save</button></td>";
    	modal_body    += "</tr></table>";

		alertify.alert()
		  .setting({
		    'basic' : true,
		    'resizable' : true,
		    onclose : function(){ $('#user_2fa').trigger('click') },
		    'message': modal_body
		  }).resizeTo(800,500).show();
	}
	else {
		$.ajax({
		    type: 'POST',
		    url: base_url + '/' + $("#user_area").val() + '/my-profile-2fa-disable',
			success: function(result) {
	            if (result.success === true) {
	            	alertify.success(result.message,3,function() {
	            		window.location.reload();
		            });
	            }
	            else {
	            	alertify.error(result.message,3);
	            }
	        },
			async:true
	    });		
	}

// https://apps.apple.com/us/app/google-authenticator/id388497605
// https://apps.apple.com/us/app/twilio-authy/id494168017#?platform=iphone

}

function update_2fa(end_point) {

	$.ajax({
	    type: 'POST',
	    url: base_url + '/' + end_point + '/my-profile-2fa',
		data: 'secret=' + $("#2fa_secret").val() + '&code=' + $("#2fa_code").val(),
		success: function(result) {
            if (result.success === true) {
            	alertify.success(result.message,3,function() {
            		window.location.reload();
	            });
            }
            else {
            	alertify.error(result.message,3);
            }
        },
		async:true
    });

}