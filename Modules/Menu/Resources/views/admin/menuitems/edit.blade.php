@extends('layouts.master')

@section('content-header')
<h1>
    {{ trans('menu::menu.titles.edit menu item') }}
</h1>
<ol class="breadcrumb">
    <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li><a href="{{ route('admin.menu.menu.index') }}">{{ trans('menu::menu.breadcrumb.menu') }}</a></li>
    <li>{{ trans('menu::menu.breadcrumb.edit menu item') }}</li>
</ol>
@stop

@section('content')
{!! Form::open(['route' => ['dashboard.menuitem.update', $menu->id, $menuItem->id], 'method' => 'put']) !!}
<div class="row">
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">{{ trans('core::core.title.translatable fields') }}</h3>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <?php $i = 0; ?>
                        @foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
                            <?php $i++; ?>
                            <li class="{{ App::getLocale() == $locale ? 'active' : '' }}">
                                <a href="#tab_{{ $i }}" data-toggle="tab">
                                    {{ trans('core::core.tab.'. strtolower($language['name'])) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        <?php $i = 0; ?>
                        @foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
                            <?php $i++; ?>
                            <div class="tab-pane {{ App::getLocale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                                @include('menu::admin.menuitems.partials.edit-trans-fields', ['lang' => $locale])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">{{ trans('core::core.title.non translatable fields') }}</h3>
            </div>
            <div class="box-body">
                @include('menu::admin.menuitems.partials.edit-fields')
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.update') }}</button>
            <a class="btn btn-danger pull-right btn-flat" href="{{ route('admin.menu.menu.edit', [$menu->id])}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">{{ trans('menu::menu-items.link-type.link type') }}</h3>
            </div>
            <div class="box-body">
                <div class="radio">
                    <input type="radio" id="link-page" name="link_type"
                           value="page"{{ $menuItem->link_type === 'page' ? ' checked' : '' }}>
                    <label for="link-page">{{ trans('menu::menu-items.link-type.page') }}</label>
                </div>
                <div class="radio">
                    <input type="radio" id="link-internal" name="link_type"
                           value="internal"{{ $menuItem->link_type === 'internal' ? ' checked' : '' }}>
                    <label for="link-internal">{{ trans('menu::menu-items.link-type.internal') }}</label>
                </div>
                <div class="radio">
                    <input type="radio" id="link-external" name="link_type"
                           value="external"{{ $menuItem->link_type === 'external' ? ' checked' : '' }}>
                    <label for="link-external">{{ trans('menu::menu-items.link-type.external') }}</label>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>b</code></dt>
        <dd>{{ trans('core::core.back to index', ['name' => 'menu']) }}</dd>
    </dl>
@stop

@push('js-stack')
<script>
$( document ).ready(function() {
    $(document).keypressAction({
        actions: [
            { key: 'b', route: "<?= route('admin.menu.menu.edit', [$menu->id]) ?>" }
        ]
    });
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });
    $('input[type="checkbox"]').on('ifChecked', function(){
        $(this).parent().find('input[type=hidden]').remove();
    });

    $('input[type="checkbox"]').on('ifUnchecked', function(){
        var name = $(this).attr('name'),
            input = '<input type="hidden" name="' + name + '" value="0" />';
        $(this).parent().append(input);
    });

    $('.link-type-depended').hide();
    $('.link-{{ $menuItem->link_type }}').fadeIn();
    $('[name="link_type"]').iCheck({
        checkboxClass: 'icheckbox_minimal',
        radioClass: 'iradio_flat-blue'
    }).on('ifChecked',function(){
        $('.link-type-depended').hide();
        $('.link-'+$(this).val()).fadeIn();
    });
});
</script>
@endpush
