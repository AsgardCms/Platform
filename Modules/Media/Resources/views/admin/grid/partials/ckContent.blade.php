<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ trans('media::media.file picker') }}</title>
    {!! Theme::style('vendor/bootstrap/dist/css/bootstrap.min.css') !!}
    {!! Theme::style('vendor/admin-lte/dist/css/AdminLTE.css') !!}
    {!! Theme::style('vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') !!}
    {!! Theme::style('vendor/font-awesome/css/font-awesome.min.css') !!}
    <link href="{!! Module::asset('media:css/dropzone.css') !!}" rel="stylesheet" type="text/css"/>
    <style>
        body {
            background: #ecf0f5;
            margin-top: 20px;
        }

        .dropzone {
            border: 1px dashed #CCC;
            min-height: 227px;
            margin-bottom: 20px;
            display: none;
        }
    </style>
    <script>
        AuthorizationHeaderValue = 'Bearer {{ $currentUser->getFirstApiKey() }}';
    </script>
    @include('partials.asgard-globals')
</head>
<body>
<div class="container">
    <div class="row">
        <form method="POST" class="dropzone">
            {!! Form::token() !!}
        </form>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">{{ trans('media::media.choose file') }}</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool jsShowUploadForm" data-toggle="tooltip" title=""
                            data-original-title="Upload new">
                        <i class="fa fa-cloud-upload"></i>
                        Upload new
                    </button>
                </div>
            </div>
            <div class="box-body">
                <table class="data-table table table-bordered table-hover jsFileList data-table">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>{{ trans('core::core.table.thumbnail') }}</th>
                        <th>{{ trans('media::media.table.filename') }}</th>
                        <th>{{ trans('core::core.table.actions') }}</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>{{ trans('core::core.table.thumbnail') }}</th>
                        <th>{{ trans('media::media.table.filename') }}</th>
                        <th>{{ trans('core::core.table.actions') }}</th>
                    </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>
</div>
{!! Theme::script('vendor/jquery/jquery.min.js') !!}
{!! Theme::script('vendor/bootstrap/dist/js/bootstrap.min.js') !!}
{!! Theme::script('vendor/datatables.net/js/jquery.dataTables.min.js') !!}
{!! Theme::script('vendor/datatables.net-bs/js/dataTables.bootstrap.min.js') !!}
<script src="{!! Module::asset('media:js/dropzone.js') !!}"></script>
<?php $config = config('asgard.media.config'); ?>
<script>
    var maxFilesize = '<?php echo $config['max-file-size'] ?>',
        acceptedFiles = '<?php echo $config['allowed-types'] ?>';
</script>
<script src="{!! Module::asset('media:js/init-dropzone.js') !!}"></script>
<script>
    $(document).ready(function () {
        $('.jsShowUploadForm').on('click', function (event) {
            event.preventDefault();
            $('.dropzone').fadeToggle();
        });
    });
</script>

<?php $locale = App::getLocale(); ?>
<script type="text/javascript">
    $(function () {
        function insertImageEvent (e) {
            e.preventDefault();
            function getUrlParam(paramName) {
                var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
                var match = window.location.search.match(reParam);

                return ( match && match.length > 1 ) ? match[1] : null;
            }

            var funcNum = getUrlParam('CKEditorFuncNum');

            window.opener.CKEDITOR.tools.callFunction(funcNum, $(this).data('file-path'));
            window.close();
        }
        $('.data-table').dataTable({
            "paginate": true,
            "lengthChange": true,
            "lengthMenu": [25, 50, 100],
            "pageLength": 25,
            "filter": true,
            "sort": true,
            "info": true,
            "autoWidth": true,
            "order": [[0, "desc"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('media.grid.ckeditor') }}"
            },
            "columns": [
                {"data": "id", 'searchable': true, "orderable": true},
                {"data": "small_thumb", 'searchable': false, "orderable": false},
                {"data": "filename", 'searchable': true, "orderable": false},
                {"data": "path", 'searchable': false, "orderable": false},
            ],
            createdRow: function (row, data, dataIndex) {
                console.log(row);
                $(row).find('td:eq(0)').html(data.id);
                if (data.is_image === true) {
                    $(row).find('td:eq(1)').html('<img src="'+data.small_thumb+'"/>');
                } else {
                    $(row).find('td:eq(1)').html('<i class="fa '+data.fa_icon+'" style="font-size: 20px;"></i>');
                }

                $(row).find('td:eq(2)').html(data.filename);
                let thumbnailMenu = '<div class="btn-group">\n' +
                '                                                                        <button type="button" class="btn btn-primary btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">\n' +
                '                                        Chèn tệp <span class="caret"></span>\n' +
                '                                    </button>\n' +'<ul class="dropdown-menu" role="menu">\n';
                $.each(data.thumbnails, function(index, thumbnail) {
                    thumbnailMenu += '<li data-file-path="'+thumbnail.path+'" data-id="'+data.id+'" data-media-type="'+data.media_type+'" data-mimetype="'+data.mimetype+'" class="jsInsertImage">\n' +
                        thumbnail.name+' ('+thumbnail.size+')' +
                        '  </li>\n';
                });
                thumbnailMenu += '</ul>';
                $(row).find('td:eq(3)').html(thumbnailMenu).find('.jsInsertImage').on('click', insertImageEvent);
            },
            "language": {
                "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
            }
        });
    });
</script>
