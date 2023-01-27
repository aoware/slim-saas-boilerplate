$(document).ready(function(){

	var elem = document.querySelector('.js-switch');
    var switchery = new Switchery(elem,  { color : '#206bc4', size: 'small' });

});

function delete_user(id) {

    var user_id = id;

    alertify.confirm('Delete User id ' + user_id,
        function(){ 
			$.ajax({
	    	    type: 'GET',
	    		url: base_url + '/backoffice/user/' + user_id + '/delete',
	    		success: function(result) {
	
	                if (result.success === false) {
						alertify.error(result.message,2);
	                }
	                else {
						alertify.success(result.message,2,
						    function() {
							    window.location.reload();
						    }
						);
	                	
	                }
	
	            },
	    		async:true
	        }); 
		}
	);

    return false;

}