<ul class="nav nav-tabs">
<?php $i = 0; ?>
<?php foreach(LaravelLocalization::getSupportedLocales() as $locale => $language): ?>
    <?php $i++; ?>
    <li class="{{ App::getLocale() == $locale ? 'active' : '' }}">
        <a href="#tab_{{ $i }}" data-toggle="tab">{{ trans('core::core.tab.'. strtolower($language['name'])) }}</a>
    </li>
<?php endforeach; ?>
</ul>
