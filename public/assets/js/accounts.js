function delete_account(id) {

    var account_id = id;

    alertify.confirm('Delete Account id ' + account_id,
        function(){ 
			$.ajax({
	    	    type: 'GET',
	    		url: base_url + '/backoffice/account/' + account_id + '/delete',
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