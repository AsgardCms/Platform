<?php

namespace Modules\Translation\Importers;

use Illuminate\Support\Arr;
use League\Csv\Reader;
use Modules\Translation\Repositories\TranslationRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TranslationsImporter
{
    /**
     * @var TranslationRepository
     */
    private $translation;

    public function __construct(TranslationRepository $translation)
    {
        $this->translation = $translation;
    }

    public function import(UploadedFile $file)
    {
        $csv = Reader::createFromPath($file->getRealPath());
        $csv->detectDelimiterList(5, [',', ';']);
        $headers = $csv->fetchOne();
        $csv->setOffset(1);

        $csv->each(function ($row) use ($headers) {
            try {
                $row = array_combine($headers, $row);
            } catch (\Exception $e) {
                return true;
            }

            $key = Arr::get($row, 'key');
            array_shift($row);
            $data = [];
            foreach ($row as $locale => $value) {
                $data[$locale] = ['value' => $value];
            }

            $this->translation->updateFromImport($key, $data);

            return true;
        });
    }
}
