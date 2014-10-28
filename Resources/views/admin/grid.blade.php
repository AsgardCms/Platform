<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>File picker</title>
  <link href="{{{ Module::asset('core', 'css/vendor/bootstrap.min.css') }}}" rel="stylesheet" type="text/css" />
  <script src="{{{ Module::asset('core', 'js/vendor/jquery.min.js') }}}"></script>
</head>
<body>
  <h1>Choose Image</h1>

  <?php if ($files): ?>
  <ul>
      <?php foreach($files as $file): ?>
          <li>
              <img src="{{ $file->path }}" alt=""/>
              <a class="jsInsertImage btn btn-primary btn-flat" href="">Insert</a>
          </li>
      <?php endforeach; ?>
  </ul>
  <?php endif; ?>

  <script>
    $( document ).ready(function() {
        $('.jsInsertImage').on('click', function(e) {
            e.preventDefault();
            function getUrlParam( paramName ) {
                var reParam = new RegExp( '(?:[\?&]|&)' + paramName + '=([^&]+)', 'i' ) ;
                var match = window.location.search.match(reParam) ;

                return ( match && match.length > 1 ) ? match[ 1 ] : null ;
            }
            var funcNum = getUrlParam( 'CKEditorFuncNum' );

            window.opener.CKEDITOR.tools.callFunction( funcNum, $(this).parent().find('img').attr('src') );
            window.close();
        });
    });
  </script>
</body>
</html>
