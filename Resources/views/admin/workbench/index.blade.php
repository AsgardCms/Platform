@extends('core::layouts.master')

@section('content-header')
<h1>
    Module helpers
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Workbench</li>
</ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1-1" data-toggle="tab">Generator</a></li>
                <li><a href="#tab_2-2" data-toggle="tab">Migrations</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1-1">
                    @include('flash::message')
                    {!! Form::open(['route' => 'dashboard.workbench.generate.index', 'method' => 'post']) !!}
                    <div class="box-body">
                        <div class='form-group'>
                            {!! Form::label('name', 'Module Name:') !!}
                            {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Module Name']) !!}
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-flat">Generate new module</button>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="tab-pane" id="tab_2-2">
                    @include('flash::message')
                    {!! Form::open(['route' => 'dashboard.workbench.migrate.index', 'method' => 'post']) !!}
                        <div class="box-body">
                            <div class='form-group'>
                                {!! Form::label('name', 'Module Name:') !!}
                                {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Module Name']) !!}
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-flat">Migrate</button>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop