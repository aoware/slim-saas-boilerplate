$(document).ready(function(){

	var elem = document.querySelector('.js-switch');
    var switchery = new Switchery(elem,  { color : '#206bc4', size: 'small' });

});

function delete_administrator(id) {

    var administrator_id = id;

    alertify.confirm('Delete Administator id ' + administrator_id,
        function(){ 
			$.ajax({
	    	    type: 'GET',
	    		url: base_url + '/backoffice/administrator/' + administrator_id + '/delete',
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