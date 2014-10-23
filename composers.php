<?php

View::creator('core::partials.sidebar-nav', 'Modules\Core\Composers\SidebarViewCreator');
View::composer('core::layouts.master', 'Modules\Core\Composers\MasterViewComposer');
