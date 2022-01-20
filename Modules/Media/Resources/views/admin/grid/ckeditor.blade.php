@include('media::admin.grid.partials.content', ['isWysiwyg' => true])
<script>
    $(document).ready(function () {
        $('.jsInsertImage').on('click', function (e) {
            e.preventDefault();
            function getUrlParam(paramName) {
                var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
                var match = window.location.search.match(reParam);

                return ( match && match.length > 1 ) ? match[1] : null;
            }

            var imageButton = $(this);
            var funcNum = getUrlParam('CKEditorFuncNum');

            window.opener.CKEDITOR.tools.callFunction(funcNum, imageButton.data('file-path'), function () {
                var dialog = this.getDialog();
                if (dialog.getName() !== 'image') {
                    return;
                }
                var altField = dialog.getContentElement('info', 'txtAlt');
                var altValue = imageButton.data('alt');
                if (altField && altValue) {
                    altField.setValue(altValue);
                }
            });
            window.close();
        });
    });
</script>
</body>
</html>
