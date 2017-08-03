<?php

use Illuminate\Support\HtmlString;
use Illuminate\Support\ViewErrorBag;

/*
|--------------------------------------------------------------------------
| Translatable fields
|--------------------------------------------------------------------------
*/
/*
 * Add a translatable input field
 *
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param string $lang the language of the field
 * @param null|object $object The entity of the field
 *
 * @return HtmlString
 */
Form::macro('i18nInput', function ($name, $title, ViewErrorBag $errors, $lang, $object = null, array $options = []) {
    $options = array_merge(['class' => 'form-control', 'placeholder' => $title], $options);

    $string = "<div class='form-group " . ($errors->has($lang . '.' . $name) ? ' has-error' : '') . "'>";
    $string .= Form::label("{$lang}[{$name}]", $title);

    if (is_object($object)) {
        $currentData = $object->hasTranslation($lang) ? $object->translate($lang)->{$name} : '';
    } else {
        $currentData = '';
    }

    $string .= Form::text("{$lang}[{$name}]", old("{$lang}[{$name}]", $currentData), $options);
    $string .= $errors->first("{$lang}.{$name}", '<span class="help-block">:message</span>');
    $string .= '</div>';

    return new HtmlString($string);
});

/*
 * Add a translatable input field of specified type
 *
 * @param string $type The type of field
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param string $lang the language of the field
 * @param null|object $object The entity of the field
 *
 * @return HtmlString
 */
Form::macro('i18nInputOfType', function ($type, $name, $title, ViewErrorBag $errors, $lang, $object = null, array $options = []) {
    $options = array_merge(['class' => 'form-control', 'placeholder' => $title], $options);

    $string = "<div class='form-group " . ($errors->has($lang . '.' . $name) ? ' has-error' : '') . "'>";
    $string .= Form::label("{$lang}[{$name}]", $title);

    if (is_object($object)) {
        $currentData = $object->hasTranslation($lang) ? $object->translate($lang)->{$name} : '';
    } else {
        $currentData = '';
    }

    $string .= Form::input($type, "{$lang}[{$name}]", old("{$lang}[{$name}]", $currentData), $options);
    $string .= $errors->first("{$lang}.{$name}", '<span class="help-block">:message</span>');
    $string .= '</div>';

    return new HtmlString($string);
});

/*
 * Add a translatable textarea field
 *
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param string $lang the language of the field
 * @param null|object $object The entity of the field
 *
 * @return HtmlString
 */
Form::macro('i18nTextarea', function ($name, $title, ViewErrorBag $errors, $lang, $object = null, array $options = []) {
    $options = array_merge(['class' => 'ckeditor', 'rows' => 10, 'cols' => 10], $options);

    $string = "<div class='form-group " . ($errors->has($lang . '.' . $name) ? ' has-error' : '') . "'>";
    $string .= Form::label("{$lang}[{$name}]", $title);

    if (is_object($object)) {
        $currentData = $object->hasTranslation($lang) ? $object->translate($lang)->{$name} : '';
    } else {
        $currentData = '';
    }

    $string .= Form::textarea("{$lang}[$name]", old("{$lang}[{$name}]", $currentData), $options);
    $string .= $errors->first("{$lang}.{$name}", '<span class="help-block">:message</span>');
    $string .= '</div>';

    return new HtmlString($string);
});

/*
 * Add a translatable checkbox input field
 *
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param string $lang the language of the field
 * @param null|object $object The entity of the field
 *
 * @return HtmlString
 */
Form::macro('i18nCheckbox', function ($name, $title, ViewErrorBag $errors, $lang, $object = null) {
    $string = "<div class='checkbox" . ($errors->has($lang . '.' . $name) ? ' has-error' : '') . "'>";
    $string .= "<label for='{$lang}[{$name}]'>";
    $string .= "<input id='{$lang}[{$name}]' name='{$lang}[{$name}]' type='checkbox' class='flat-blue'";

    if (is_object($object)) {
        $currentData = $object->hasTranslation($lang) ? (bool)$object->translate($lang)->{$name} : '';
    } else {
        $currentData = false;
    }

    $oldInput = old("{$lang}.$name", $currentData) ? 'checked' : '';
    $string .= "value='1' {$oldInput}>";
    $string .= $title;
    $string .= $errors->first($name, '<span class="help-block">:message</span>');
    $string .= '</label>';
    $string .= '</div>';

    return new HtmlString($string);
});

/*
 * Add a translatable dropdown select field
 *
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param string $lang the language of the field
 * @param array $choice The choice of the select
 * @param null|array $object The entity of the field
 *
 * @return HtmlString
 */
Form::macro('i18nSelect', function ($name, $title, ViewErrorBag $errors, $lang, array $choice, $object = null, array $options = []) {
    if (array_key_exists('multiple', $options)) {
        $nameForm = "{$lang}[$name][]";
    } else {
        $nameForm = "{$lang}[$name]";
    }

    $string = "<div class='form-group dropdown" . ($errors->has($lang . '.' . $name) ? ' has-error' : '') . "'>";
    $string .= "<label for='$nameForm'>$title</label>";

    if (is_object($object)) {
        $currentData = $object->hasTranslation($lang) ? $object->translate($lang)->{$name} : '';
    } else {
        $currentData = false;
    }

    /* Bootstrap default class */
    $array_option = ['class' => 'form-control'];

    if (array_key_exists('class', $options)) {
        $array_option = ['class' => $array_option['class'] . ' ' . $options['class']];
        unset($options['class']);
    }

    $options = array_merge($array_option, $options);

    $string .= Form::select($nameForm, $choice, old($nameForm, $currentData), $options);
    $string .= $errors->first("{$lang}.{$name}", '<span class="help-block">:message</span>');
    $string .= '</div>';

    return new HtmlString($string);
});

/*
|--------------------------------------------------------------------------
| Standard fields
|--------------------------------------------------------------------------
*/
/*
 * Add an input field
 *
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param null|object $object The entity of the field
 *
 * @return HtmlString
 */
Form::macro('normalInput', function ($name, $title, ViewErrorBag $errors, $object = null, array $options = []) {
    $options = array_merge(['class' => 'form-control', 'placeholder' => $title], $options);

    $string = "<div class='form-group " . ($errors->has($name) ? ' has-error' : '') . "'>";
    $string .= Form::label($name, $title);

    if (is_object($object)) {
        $currentData = isset($object->{$name}) ? $object->{$name} : '';
    } else {
        $currentData = null;
    }

    $string .= Form::text($name, old($name, $currentData), $options);
    $string .= $errors->first($name, '<span class="help-block">:message</span>');
    $string .= '</div>';

    return new HtmlString($string);
});

/*
 * Add an input field of specified type
 *
 * @param string $type The type of field
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param null|object $object The entity of the field
 *
 * @return HtmlString
 */
Form::macro('normalInputOfType', function ($type, $name, $title, ViewErrorBag $errors, $object = null, array $options = []) {
    $options = array_merge(['class' => 'form-control', 'placeholder' => $title], $options);

    $string = "<div class='form-group " . ($errors->has($name) ? ' has-error' : '') . "'>";
    $string .= Form::label($name, $title);

    if (is_object($object)) {
        $currentData = isset($object->{$name}) ? $object->{$name} : '';
    } else {
        $currentData = null;
    }

    $string .= Form::input($type, $name, old($name, $currentData), $options);
    $string .= $errors->first($name, '<span class="help-block">:message</span>');
    $string .= '</div>';

    return new HtmlString($string);
});

/*
 * Add a textarea field
 *
 * @param string $name
 * @param string $title
 * @param ViewErrorBag $errors
 * @param null|object $object
 * @param array $options
 *
 * @return HtmlString
 */
Form::macro('normalTextarea', function ($name, $title, ViewErrorBag $errors, $object = null, array $options = []) {
    $options = array_merge(['class' => 'ckeditor', 'rows' => 10, 'cols' => 10], $options);

    $string = "<div class='form-group " . ($errors->has($name) ? ' has-error' : '') . "'>";
    $string .= Form::label($name, $title);

    if (is_object($object)) {
        $currentData = $object->{$name} ?: '';
    } else {
        $currentData = null;
    }

    $string .= Form::textarea($name, old($name, $currentData), $options);
    $string .= $errors->first($name, '<span class="help-block">:message</span>');
    $string .= '</div>';

    return new HtmlString($string);
});

/*
 * Add a checkbox input field
 *
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param null|object $object The entity of the field
 *
 * @return HtmlString
 */
Form::macro('normalCheckbox', function ($name, $title, ViewErrorBag $errors, $object = null) {
    $string = "<div class='checkbox" . ($errors->has($name) ? ' has-error' : '') . "'>";
    $string .= "<input type='hidden' value='0' name='{$name}'/>";
    $string .= "<label for='$name'>";
    $string .= "<input id='$name' name='$name' type='checkbox' class='flat-blue'";

    if (is_object($object)) {
        $currentData = isset($object->$name) && (bool)$object->$name ? 'checked' : '';
    } else {
        $currentData = false;
    }

    $oldInput = old($name, $currentData) ? 'checked' : '';
    $string .= "value='1' {$oldInput}>";
    $string .= $title;
    $string .= $errors->first($name, '<span class="help-block">:message</span>');
    $string .= '</label>';
    $string .= '</div>';

    return new HtmlString($string);
});

/*
 * Add a dropdown select field
 *
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param array $choice The choice of the select
 * @param null|array $object The entity of the field
 *
 * @return HtmlString
 */
Form::macro('normalSelect', function ($name, $title, ViewErrorBag $errors, array $choice, $object = null, array $options = []) {
    if (array_key_exists('multiple', $options)) {
        $nameForm = $name . '[]';
    } else {
        $nameForm = $name;
    }

    $string = "<div class='form-group dropdown" . ($errors->has($name) ? ' has-error' : '') . "'>";
    $string .= "<label for='$nameForm'>$title</label>";

    if (is_object($object)) {
        $currentData = isset($object->$name) ? $object->$name : '';
    } else {
        $currentData = false;
    }

    /* Bootstrap default class */
    $array_option = ['class' => 'form-control'];

    if (array_key_exists('class', $options)) {
        $array_option = ['class' => $array_option['class'] . ' ' . $options['class']];
        unset($options['class']);
    }

    $options = array_merge($array_option, $options);

    $string .= Form::select($nameForm, $choice, old($nameForm, $currentData), $options);
    $string .= $errors->first($name, '<span class="help-block">:message</span>');
    $string .= '</div>';

    return new HtmlString($string);
});

Response::macro('csv', function ($file, $filename, $status = 200, $headers = []) {
    return response($file, $status, array_merge([
        'Content-Type' => 'application/csv',
        'Content-Disposition' => "attachment; filename={$filename}",
        'Pragma' => 'no-cache',
    ], $headers));
});
