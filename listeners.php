<?php
Event::listen('Modules.User.Events.*', 'Modules\User\Listeners\SendResetCodeEmail');
Event::listen('Modules.User.Events.*', 'Modules\User\Listeners\SendRegistrationConfirmationEmail');
