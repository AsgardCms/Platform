<?php

namespace Modules\Core\Console\Installers\Traits;

trait BlockMessage
{
    public function blockMessage($title, $message, $style = 'info')
    {
        $formatter = $this->getHelperSet()->get('formatter');
        $errorMessages = [$title, $message];
        $formattedBlock = $formatter->formatBlock($errorMessages, $style, true);
        $this->line($formattedBlock);
    }
}
