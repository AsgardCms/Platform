<?php

namespace Modules\Translation\Services;

use Illuminate\Filesystem\Filesystem;
use Modules\Translation\ValueObjects\TranslationGroup;

class TranslationsWriter
{
    /**
     * @var Filesystem
     */
    private $finder;
    /**
     * @var TranslationsService
     */
    private $translationsService;

    public function __construct(TranslationsService $translationsService, Filesystem $finder)
    {
        $this->finder = $finder;
        $this->translationsService = $translationsService;
    }

    /**
     * Export all translations back to disk
     */
    public function export()
    {
        $translations = $this->translationsService->getFileAndDatabaseMergedTranslations();

        $tree = $this->makeTree($translations);

        foreach ($tree as $locale => $groups) {
            foreach ($groups as $moduleName => $fileGroup) {
                foreach ($fileGroup as $file => $data) {
                    $path = $this->getTranslationsDirectory() . $moduleName . '/' . $locale . '/' . $file . '.php';
                    $output = "<?php\n\nreturn " . var_export($data, true) . ";\n";
                    $this->finder->put($path, $output);
                }
            }
        }
    }

    /**
     * Get the module name from the given key
     * @param string $key
     * @return string
     */
    private function getModuleNameFrom($key)
    {
        return substr($key, 0, strpos($key, '::'));
    }

    /**
     * @return string
     */
    private function getTranslationsDirectory()
    {
        return __DIR__ . '/../Resources/lang/';
    }

    /**
     * Get the file name from the given key
     * @param string $key
     * @return string
     */
    private function getFileNameFrom($key)
    {
        $key = str_replace($this->getModuleNameFrom($key) . '::', '', $key);

        return substr($key, 0, strpos($key, '.'));
    }

    /**
     * Make a usable array
     * @param TranslationGroup $translations
     * @return array
     */
    private function makeTree(TranslationGroup $translations)
    {
        $tree = [];

        foreach ($translations->allRaw() as $locale => $translation) {
            foreach ($translation as $key => $trans) {
                $moduleName = $this->getModuleNameFrom($key);
                $fileName = $this->getFileNameFrom($key);
                $key = str_replace($moduleName . '::' . $fileName . '.', '', $key);

                array_set($tree[$locale][$moduleName][$fileName], $key, $trans);
            }
        }

        return $tree;
    }
}
