<?php

View::creator('core::partials.sidebar-nav', 'Modules\Core\Composers\SidebarViewCreator');
View::composer('core::partials.sidebar-nav', 'Modules\Core\Composers\SidebarViewComposer');
