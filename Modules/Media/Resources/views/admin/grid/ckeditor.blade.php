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

            var funcNum = getUrlParam('CKEditorFuncNum');

            window.opener.CKEDITOR.tools.callFunction(funcNum, $(this).data('file-path'));
            window.close();
        });
    });
</script>
</body>
</html>
