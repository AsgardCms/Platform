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
    <form action="{{ URL::route('api.media.store')}}" method="POST" class="dropzone" id="my-awesome-dropzone">
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
                        <tr>
                            <td>23/09/1990</td>
                            <td>filename</td>
                            <td>500</td>
                            <td>500</td>
                            <td>action</td>
                        </tr>
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
    $(".dropzone").dropzone({
        url: $(this).attr('action')
    });
    var tableRow = '<tr><td>23/09/2140</td><td>filename</td><td>500</td><td>500</td><td>action</td></tr>'
    $('table>tbody').append(tableRow);
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
