<?php

namespace Modules\Core\Console\Installers\Traits;

trait SectionMessage
{
    public function sectionMessage($title, $message)
    {
        $formatter = $this->getHelperSet()->get('formatter');
        $formattedLine = $formatter->formatSection(
            $title,
            $message
        );
        $this->line($formattedLine);
    }
}
