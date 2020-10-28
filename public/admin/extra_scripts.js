setCKEditors = function(){
	var textareas = $('textarea');

    for(var i=0; i < textareas.length; i++) {
        console.log(textareas[i]);
        ClassicEditor
            .create( textareas[i] )
            .catch( error => {
                console.error( error );
            } );
    }
};

$(function() {
	setCKEditors();
});