<?php

View::creator('core::partials.sidebar-nav', 'Modules\Core\Composers\SidebarViewCreator');
View::composer('core::partials.sidebar-nav', 'Modules\Core\Composers\SidebarViewComposer');

Event::listen('modules.load.start', function()
{
//    $modules = Module::enabled();
//    dd($modules);
});

Event::listen('modules.load.end', function()
{
//    $modules = Module::enabled();
//    dd($modules);
});