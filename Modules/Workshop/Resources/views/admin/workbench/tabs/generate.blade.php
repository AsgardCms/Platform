<div class="row">
    <div class="col-md-6">
        {!! Form::open(['route' => 'admin.workshop.workbench.generate.index', 'method' => 'post']) !!}
        <div class="box-body">
            <h4>{{ trans('workshop::workbench.subtitle.generate new module') }}</h4>
            <div class='form-group{{ $errors->has('name') ? ' has-error' : '' }}'>
                {!! Form::label('name', trans('workshop::workbench.form.module name')) !!}
                {!! Form::text('name', Request::old('name'), ['class' => 'form-control', 'placeholder' => trans('workshop::workbench.form.module name')]) !!}
                {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('workshop::workbench.button.generate new module') }}</button>
        </div>
        {!! Form::close() !!}
    </div>
    <div class="col-md-6">
        {!! Form::open(['route' => 'admin.workshop.workbench.install.index', 'method' => 'post']) !!}
        <div class="box-body">
            <h4>{{ trans('workshop::workbench.subtitle.install new module by vendor name') }}</h4>
            <div class='form-group{{ $errors->has('vendorName') ? ' has-error' : '' }}'>
                {!! Form::label('vendorName', trans('workshop::workbench.form.vendor name of the module')) !!}
                {!! Form::text('vendorName', Request::old('vendorName'), ['class' => 'form-control', 'placeholder' => trans('workshop::workbench.form.vendor name of the module')]) !!}
                {!! $errors->first('vendorName', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="checkbox">
                <label for="subtree">
                    <input id="subtree" name="subtree" type="checkbox" class="flat-blue" value="true" /> {{ trans('workshop::workbench.form.install as subtree') }}
                </label>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('workshop::workbench.button.install new module') }}</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
