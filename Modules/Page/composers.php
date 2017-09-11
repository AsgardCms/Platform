<?php

view()->composer(['page::admin.create', 'page::admin.edit'], \Modules\Page\Composers\TemplateViewComposer::class);
