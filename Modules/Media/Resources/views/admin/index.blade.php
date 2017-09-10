@extends('layouts.master')

@section('content-header')
<h1>
    {{ trans('media::media.title.media') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li><i class="fa fa-camera"></i> {{ trans('media::media.breadcrumb.media') }}</li>
</ol>
@stop

@push('css-stack')
<link href="{!! Module::asset('media:css/dropzone.css') !!}" rel="stylesheet" type="text/css" />
<style>
.dropzone {
    border: 1px dashed #CCC;
    min-height: 227px;
    margin-bottom: 20px;
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="POST" class="dropzone">
            {!! Form::token() !!}
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <table class="data-table table table-bordered table-hover jsFileList">
                    <thead>
                        <tr>
                            <th>{{ trans('core::core.table.thumbnail') }}</th>
                            <th>{{ trans('media::media.table.filename') }}</th>
                            <th>{{ trans('core::core.table.created at') }}</th>
                            <th>{{ trans('core::core.table.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>{{ trans('core::core.table.thumbnail') }}</th>
                            <th>{{ trans('media::media.table.filename') }}</th>
                            <th>{{ trans('core::core.table.created at') }}</th>
                            <th>{{ trans('core::core.table.actions') }}</th>
                        </tr>
                    </tfoot>
                </table>
            <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>
@include('core::partials.delete-modal')
@stop

@push('js-stack')
<script src="{!! Module::asset('media:js/dropzone.js') !!}"></script>
<?php $config = config('asgard.media.config'); ?>
<script>
    var maxFilesize = '<?php echo $config['max-file-size'] ?>',
            acceptedFiles = '<?php echo $config['allowed-types'] ?>';
</script>
<script src="{!! Module::asset('media:js/init-dropzone.js') !!}"></script>

<?php $locale = App::getLocale(); ?>
<script type="text/javascript">
    $(function () {
        $('.data-table').dataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '{{ route('api.media.all') }}',
            "autoWidth": true,
            "order": [[ 2, "desc" ]],
            "language": {
                "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
            },
            "columns": [
                {
                    data: 'thumbnail',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'filename',
                    render: function (data, type, full) {
                        return '<a href="' + route('admin.media.media.edit', {media: full.id}) + '">' + data + '</a>';
                    }
                },
                {
                    data: "created_at",
                    render: function (data, type, full) {
                        return '<a href="' + route('admin.media.media.edit', {media: full.id}) + '">' + data + '</a>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, full) {
                        return '<a href="' + route('admin.media.media.edit', {media: full.id}) + '" class="btn btn-default btn-flat">' +
                            '<i class="fa fa-pencil"></i></a>' +
                            '<button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="' + route('admin.media.media.destroy', {media: full.id}) + '"><i class="fa fa-trash"></i></button>';
                    }
                }
            ],
        });
    });
</script>
@endpush
