@extends('core::layouts.master')

@section('content-header')
<h1>
    {{ trans('media::media.title.media') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li><i class="fa fa-camera"></i> {{ trans('media::media.breadcrumb.media') }}</li>
</ol>
@stop

@section('styles')
<link href="{!! Module::asset('media', 'css/dropzone.css') !!}" rel="stylesheet" type="text/css" />
<style>
.dropzone {
    border: 1px dashed #CCC;
    min-height: 227px;
    margin-bottom: 20px;
}
</style>
@stop

@section('content')
<div class="row col-md-12">
    <form action="{{ URL::route('api.media.store')}}" method="POST" class="dropzone">
        {!! Form::token() !!}
    </form>
</div>

<div class="row">
    <div class="col-md-12">
        @include('flash::message')
        <div class="box">
            <div class="box-body table-responsive">
                <table class="data-table table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('core::core.table.created at') }}</th>
                            <th>{{ trans('media::media.table.filename') }}</th>
                            <th>{{ trans('media::media.table.width') }}</th>
                            <th>{{ trans('media::media.table.height') }}</th>
                            <th>{{ trans('core::core.table.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($files): ?>
                            <?php foreach($files as $file): ?>
                                <tr>
                                    <td>
                                        <a href="{{ URL::route('dashboard.media.edit', [$file->id]) }}">
                                            {{ $file->created_at }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ URL::route('dashboard.media.edit', [$file->id]) }}">
                                            {{ $file->filename }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ URL::route('dashboard.media.edit', [$file->id]) }}">
                                            {{ $file->width }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ URL::route('dashboard.media.edit', [$file->id]) }}">
                                            {{ $file->height }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ URL::route('dashboard.media.edit', [$file->id]) }}" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-pencil"></i></a>
                                            <button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#confirmation-{{ $file->id }}"><i class="glyphicon glyphicon-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>{{ trans('core::core.table.created at') }}</th>
                            <th>{{ trans('media::media.table.filename') }}</th>
                            <th>{{ trans('media::media.table.width') }}</th>
                            <th>{{ trans('media::media.table.height') }}</th>
                            <th>{{ trans('core::core.table.actions') }}</th>
                        </tr>
                    </tfoot>
                </table>
            <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="{!! Module::asset('media', 'js/dropzone.js') !!}"></script>
<script>
$( document ).ready(function() {
    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone(".dropzone", {
        url: $(this).attr('action'),
        autoProcessQueue: true
    });
    myDropzone.on("success", function(file, http) {
        var tableRow = '<tr><td>' + http.created_at + '</td><td>'+http.filename+'</td><td></td><td></td><td>action</td></tr>';
        var elem = $(tableRow).css('display', 'none');
        $('table tbody').prepend(elem);
        elem.fadeIn();
    });
});
</script>
<?php $locale = App::getLocale(); ?>
<script type="text/javascript">
    $(function () {
        $('.data-table').dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": true,
            "oLanguage": {
                "sUrl": '<?php echo Module::asset('core', "js/vendor/datatables/{$locale}.json") ?>'
            },
            "aoColumns": [
                null,
                null,
                null,
                null,
                { "bSortable": false }
            ]
        });
    });
</script>
@stop
