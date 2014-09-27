@include('flash::message')
{!! Form::open(['route' => 'dashboard.workbench.migrate.index', 'method' => 'post']) !!}
    <div class="box-body">
        <div class='form-group{{ $errors->has('module') ? ' has-error' : '' }}'>
            {!! Form::label('module', 'Module Name:') !!}
            {!! Form::select('module', $modules, null, ['class' => 'form-control']) !!}
            {!! $errors->first('module', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary btn-flat">Migrate</button>
    </div>
{!! Form::close() !!}