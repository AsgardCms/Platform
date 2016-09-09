<?php

namespace Modules\Workshop\Console;

use Illuminate\Console\Command;
use Modules\Workshop\Scaffold\Theme\ThemeScaffold;

class ThemeScaffoldCommand extends Command
{
    protected $signature = 'asgard:theme:scaffold';
    protected $description = 'Scaffold a new theme';
    /**
     * @var ThemeScaffold
     */
    private $themeScaffold;

    public function __construct(ThemeScaffold $themeScaffold)
    {
        parent::__construct();
        $this->themeScaffold = $themeScaffold;
    }

    public function fire()
    {
        $themeName = $this->ask('Please enter the theme name in the following format: vendor/name');
        list($vendor, $name) = $this->separateVendorAndName($themeName);

        $type = $this->choice('Would you like to create a front end or backend theme ?', ['Frontend', 'Backend'], 0);

        $this->themeScaffold->setName($name)->setVendor($vendor)->forType(strtolower($type))->generate();

        $this->info("Generated a fresh theme called [$themeName]. You'll find it in the Themes/ folder");
    }

    /**
     * Extract the vendor and module name as two separate values
     * @param  string $fullName
     * @return array
     */
    private function separateVendorAndName($fullName)
    {
        $explodedFullName = explode('/', $fullName);

        return [
            $explodedFullName[0],
            ucfirst($explodedFullName[1]),
        ];
    }
}
