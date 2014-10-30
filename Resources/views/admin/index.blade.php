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
                            <th>{{ trans('media::media.table.filename') }}</th>
                            <th>{{ trans('core::core.table.thumbnail') }}</th>
                            <th>{{ trans('media::media.table.filename') }}</th>
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
                                        <img src="{{ Imagy::getThumbnail($file->path, 'smallThumb') }}" alt=""/>
                                    </td>
                                    <td>
                                        <a href="{{ URL::route('dashboard.media.edit', [$file->id]) }}">
                                            {{ $file->filename }}
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
                            <th>{{ trans('core::core.table.thumbnail') }}</th>
                            <th>{{ trans('media::media.table.filename') }}</th>
                            <th>{{ trans('core::core.table.actions') }}</th>
                        </tr>
                    </tfoot>
                </table>
            <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>
<?php if ($files): ?>
    <?php foreach($files as $file): ?>
    <!-- Modal -->
    <div class="modal fade" id="confirmation-{{ $file->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('core::core.modal.title') }}</h4>
                </div>
                <div class="modal-body">
                    {{ trans('core::core.modal.confirmation-message') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('core::core.button.cancel') }}</button>
                    {!! Form::open(['route' => ['dashboard.media.destroy', $file->id], 'method' => 'delete', 'class' => 'pull-left']) !!}
                        <button type="submit" class="btn btn-danger btn-flat"><i class="glyphicon glyphicon-trash"></i> {{ trans('core::core.button.delete') }}</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
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
        var tableRow = '<tr><td>' + http.created_at + '</td><td></td><td>'+http.filename+'</td><td></td></tr>';
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
                { "bSortable": false }
            ]
        });
    });
</script>
@stop
