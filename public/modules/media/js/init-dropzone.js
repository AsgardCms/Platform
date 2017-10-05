$( document ).ready(function() {
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone(".dropzone", {
        url: Asgard.dropzonePostUrl,
        autoProcessQueue: true,
        maxFilesize: maxFilesize,
        acceptedFiles : acceptedFiles
    });
    myDropzone.on("queuecomplete", function(file, http) {
        window.setTimeout(function(){
            location.reload();
        }, 1000);
    });
    myDropzone.on("sending", function(file, xhr, fromData) {
        xhr.setRequestHeader("Authorization", AuthorizationHeaderValue);
        if ($('.alert-danger').length > 0) {
            $('.alert-danger').remove();
        }
    });
    myDropzone.on("error", function(file, errorMessage) {
        let html;
        if (typeof(errorMessage) === 'object') {
            html = '<div class="alert alert-danger" role="alert">' + errorMessage.errors.file.join(', ') + '</div>';
        } else {
            html = '<div class="alert alert-danger" role="alert">' + errorMessage + '</div>';
        }
        $('.dropzone').first().parent().prepend(html);
        setTimeout(function() {
            myDropzone.removeFile(file);
        }, 2000);
    });
});
