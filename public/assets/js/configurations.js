function delete_configuration(id) {

    var definition_id = id;

    alertify.confirm('Delete Configuration Definition id ' + definition_id,
        function(){
			$.ajax({
	    	    type: 'GET',
	    		url: base_url + '/backoffice/configuration/' + definition_id + '/delete',
	    		success: function(result) {

	                if (result.success === false) {
						alertify.error(result.message,3);
	                }
	                else {
						alertify.success(result.message,3,
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