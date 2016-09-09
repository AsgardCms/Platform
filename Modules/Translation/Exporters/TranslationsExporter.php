<?php

namespace Modules\Translation\Exporters;

use League\Csv\Writer;
use Modules\Translation\Services\TranslationsService;
use SplTempFileObject;

class TranslationsExporter
{
    /**
     * @var TranslationsService
     */
    private $translations;
    private $filename = 'translations_';

    public function __construct(TranslationsService $translations)
    {
        $this->translations = $translations;
    }

    public function export()
    {
        $data = $this->formatData();
        $keys = array_keys($data[0]);

        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->insertOne($keys);
        $csv->insertAll($data);

        return (string) $csv;
    }

    /**
     * Get the filename
     * @return string
     */
    public function getFileName()
    {
        return $this->filename . time() . '.csv';
    }

    /**
     * @return array
     */
    private function formatData()
    {
        $translations = $this->translations->getFileAndDatabaseMergedTranslations();
        $translations = $translations->all();

        $data = [];
        foreach ($translations as $key => $translation) {
            $data[] = array_merge(['key' => $key], $translation);
        }

        return $data;
    }
}
