$(document).ready(function(){

    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

    elems.forEach(function(html) {
      var switchery = new Switchery(html, { color : '#206bc4', size: 'small' });
    });

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