alertify.defaults.glossary.title = '{{ brand_name }}'; 

const base_url = '{{ base_url }}';

$.ajaxSetup({
    beforeSend: function(xhr) {
        xhr.setRequestHeader('Accept','application/json');
    }
});