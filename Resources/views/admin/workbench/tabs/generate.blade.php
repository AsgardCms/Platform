@include('flash::message')
<div class="row">
    <div class="col-md-6">
        {!! Form::open(['route' => 'dashboard.workbench.generate.index', 'method' => 'post']) !!}
        <div class="box-body">
            <h4>Generate a new module</h4>
            <div class='form-group{{ $errors->has('name') ? ' has-error' : '' }}'>
                {!! Form::label('name', 'Module Name:') !!}
                {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Module Name']) !!}
                {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary btn-flat">Generate new module</button>
        </div>
        {!! Form::close() !!}
    </div>
    <div class="col-md-6">
        {!! Form::open(['route' => 'dashboard.workbench.install.index', 'method' => 'post']) !!}
        <div class="box-body">
            <h4>Install a module by vendor/name</h4>
            <div class='form-group{{ $errors->has('vendorName') ? ' has-error' : '' }}'>
                {!! Form::label('vendorName', 'vendor/name of the module:') !!}
                {!! Form::text('vendorName', Input::old('vendorName'), ['class' => 'form-control', 'placeholder' => 'Module Name']) !!}
                {!! $errors->first('vendorName', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="checkbox">
                <label for="subtree">
                    <input id="subtree" name="subtree" type="checkbox" class="flat-blue" value="true" /> Install as a subtree?
                </label>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary btn-flat">Install new module</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>