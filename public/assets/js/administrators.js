function delete_administrator(id) {

	var base_url = $("#base_url").val();

    alertify.confirm('Delete Administator id ' + id,
        function(){ 
			$.ajax({
	    	    type: 'GET',
	    		url: base_url + '/backoffice/administrator/' + id + '/delete',
	    		success: function(result) {
	
	                if (result.success === false) {
						alertify.error(result.message,2);
	                }
	                else {
						alertify.success(result.message,2);
	                	window.location.reload();
	                }
	
	            },
	    		async:true
	        }); 
		}
	);

    return false;

}