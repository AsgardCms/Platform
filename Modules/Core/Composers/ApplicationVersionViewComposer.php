<?php

namespace Modules\Core\Composers;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ApplicationVersionViewComposer
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Repository
     */
    private $cache;
    /**
     * @var Request
     */
    private $request;

    public function __construct(Filesystem $filesystem, Repository $cache, Request $request)
    {
        $this->filesystem = $filesystem;
        $this->cache = $cache;
        $this->request = $request;
    }

    public function compose(View $view)
    {
        if ($this->onBackend() === false) {
            return;
        }
        $view->with('version', $this->getAppVersion());
    }

    private function onBackend()
    {
        $url = $this->request->url();
        if (str_contains($url, config('asgard.core.core.admin-prefix'))) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    private function getAppVersion()
    {
        $composerFile = $this->getComposerFile();

        return isset($composerFile->version) ? $composerFile->version : '1.0';
    }

    /**
     * Get the decoded contents from the main composer.json file
     * @return object
     */
    private function getComposerFile()
    {
        $appName = str_slug(config('app.name'));

        $composerFile = $this->cache->remember("app.version.$appName", 1440, function () {
            return $this->filesystem->get('composer.json');
        });

        return json_decode($composerFile);
    }
}
