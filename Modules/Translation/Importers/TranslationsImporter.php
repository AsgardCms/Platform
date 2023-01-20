<?php

namespace Modules\Translation\Importers;

use Illuminate\Support\Arr;
use League\Csv\Reader;
use League\Csv\Statement;
use function League\Csv\delimiter_detect;
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
        $csv = Reader::createFromPath($file->getRealPath(), 'r');
        delimiter_detect($csv, [',', ';', "\t"], 10);
        $headers = $csv->fetchOne();

        $stmt = Statement::create()->offset(1);
        $records = $stmt->process($csv);
        foreach ($records as $offset => $row) {
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
        }
    }
}
