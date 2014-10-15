<?php namespace Modules\Core\Internationalisation;

/**
 * Class Helper
 * @package Modules\Core\Internationalisation
 */
class Helper
{
    /**
     * Save the given model properties in all given languages
     * @param $model
     * @param $data
     */
    public static function saveTranslated($model, $data)
    {
        foreach ($data as $lang => $value) {
            if (is_array($value)) {
                foreach ($value as $key => $input) {
                    $model->translate($lang)->$key = $input;
                }
            }
        }
        $model->save();
    }

    /**
     * Separate the input fields into their own language key
     * @param $data
     * @return array
     */
    public static function separateLanguages($data)
    {
        $cleanedData = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $lang => $input) {
                    $cleanedData[$lang][$key] = $input;
                }
            } else {
                $cleanedData[$key] = $value;
            }
        }
        return $cleanedData;
    }

    /**
     * Create the given model and save its translated attributes
     * @param $model
     * @param $data
     */
    public static function createTranslatedFields($model, $data)
    {
        $model = new $model;
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $lang => $input) {
                    $model->translate($lang)->$key = $input;
                }
            }
        }
        $model->save();
    }
}