@extends('core::layouts.master')

@section('content-header')
<h1>
    {{ trans('user::roles.title.roles') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li class="active">{{ trans('user::roles.breadcrumb.roles') }}</li>
</ol>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="row">
            @include('flash::message')
            <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                <a href="{{ URL::route('dashboard.role.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                    <i class="fa fa-pencil"></i> {{ trans('user::roles.button.new-role') }}
                </a>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <table class="data-table table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('user::users.table.created-at') }}</th>
                            <th>{{ trans('user::roles.table.name') }}</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($roles->count() > 0): ?>
                        <?php foreach($roles as $role): ?>
                            <tr>
                                <td>
                                    <a href="{{ URL::route('dashboard.role.edit', [$role->id]) }}">
                                        {{ $role->created_at }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ URL::route('dashboard.role.edit', [$role->id]) }}">
                                        {{ $role->name }}
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ URL::route('dashboard.role.edit', [$role->id]) }}" class="btn btn-default btn-flat"><i class="glyphicon glyphicon-pencil"></i></a>
                                        <button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#confirmation-{{ $role->id }}"><i class="glyphicon glyphicon-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>{{ trans('user::users.table.created-at') }}</th>
                            <th>{{ trans('user::roles.table.name') }}</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
            <!-- /.box-body -->
            </div>
        <!-- /.box -->
    </div>
<!-- /.col (MAIN) -->
</div>
</div>

<?php if ($roles->count() > 0): ?>
    <?php foreach($roles as $role): ?>
    <!-- Modal -->
    <div class="modal fade" id="confirmation-{{ $role->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('core::core.modal.title') }}</h4>
                </div>
                <div class="modal-body">
                    {{ trans('user::users.modal.confirmation-message') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('core::core.button.cancel') }}</button>
                    {!! Form::open(['route' => ['dashboard.role.destroy', $role->id], 'method' => 'delete', 'class' => 'pull-left']) !!}
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
<script type="text/javascript">
    $(function () {
        $('.data-table').dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": true,
            "aoColumns": [
                null,
                null,
                { "bSortable": false }
            ]
        });
    });
</script>
@stop
