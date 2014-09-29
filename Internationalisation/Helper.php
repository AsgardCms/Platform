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