$( document ).ready(function() {
    Dropzone.autoDiscover = false;
    
    /* Adaptation to resumable uploads
     * src: https://github.com/enyo/dropzone/issues/339#issuecomment-138644461
     */
    var originalDropzone = Dropzone.prototype.uploadFiles;
    
    Dropzone.prototype.uploadFiles = function (files) {
        var resumable = new Resumable ({
            target: Asgard.dropzonePostUrl,
            maxFiles: Dropzone.prototype.defaultOptions.maxFiles || 10,
            simultaneousUploads: Dropzone.prototype.defaultOptions.parallelUploads,
            headers: { 
                'Authorization': AuthorizationHeaderValue
            },
            testChunks: false
        });

        if (resumable.support) {
            for (var j = 0; j < files.length; j++) {
                var fileLocal = files[j];
                resumable.addFile(fileLocal);
            }

            resumable.on('fileAdded', function (file) {
                resumable.upload();
            });

            resumable.on('fileProgress', function (file) {
                var progressValue = Math.floor(resumable.progress() * 100);
                Dropzone.prototype.defaultOptions.uploadprogress(file.file, progressValue, null);
            });

            resumable.on('fileSuccess', (function(_this) {
                return function (file) {
                    return _this._finished([file.file], "success", null);
                }
            })(this));

            resumable.on('error', (function(_this) {
                return function (message, file) {
                    return _this._errorProcessing([file.file], message, null);
                }
            })(this));
        }  else {
            //Fallback to original implementation
            return originalDropzone.apply(this, arguments);
        }
    };
    
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
        var html = '<div class="alert alert-danger" role="alert">' + errorMessage + '</div>';
        $('.col-md-12').first().prepend(html);
        setTimeout(function() {
            myDropzone.removeFile(file);
        }, 2000);
    });
});