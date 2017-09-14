<?php

namespace Modules\Translation\Repositories\File;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Filesystem\Filesystem;
use Modules\Translation\Repositories\FileTranslationRepository as FileTranslationRepositoryInterface;

class FileTranslationRepository implements FileTranslationRepositoryInterface
{
    /**
     * @var Filesystem
     */
    private $finder;
    /**
     * @var Loader
     */
    private $loader;

    public function __construct(Filesystem $finder, Loader $loader)
    {
        $this->finder = $finder;
        $this->loader = $loader;
    }

    /**
     * Get all the translations for all modules on disk
     * @return array
     */
    public function all()
    {
        $files = $this->getTranslationFilenamesFromPaths($this->loader->paths());

        $translations = [];

        foreach ($files as $locale => $files) {
            foreach ($files as $namespace => $file) {
                $trans = $this->finder->getRequire($file);
                $trans = array_dot($trans);

                foreach ($trans as $key => $value) {
                    $translations[$locale]["{$namespace}.{$key}"] = $value;
                }
            }
        }

        return $translations;
    }

    /**
     * Get all of the names of the Translations files from an array of Paths.
     * Returns [ 'translationkeyprefix' => 'filepath' ]
     * @param array $paths
     * @return array
     */
    protected function getTranslationFilenamesFromPaths(array $paths)
    {
        $files   = [];
        $locales = config('laravellocalization.supportedLocales');

        foreach ($paths as $hint => $path) {
            foreach ($locales as $locale => $language) {
                $glob = $this->finder->glob("{$path}/{$locale}/*.php");

                if ($glob) {
                    foreach ($glob as $file) {
                        $category = str_replace(["$path/", ".php", "{$locale}/"], "", $file);
                        $category = str_replace("/", ".", $category);
                        $category = !is_int($hint) ? "{$hint}::{$category}" : $category;

                        $files[$locale][$category] = $file;
                    }
                }
            }
        }

        return $files;
    }
}
