<?php

if (! function_exists('on_route')) {
    function on_route($route)
    {
        return Route::current() ? Route::is($route) : false;
    }
}

if (! function_exists('locale')) {
    function locale($locale = null)
    {
        if (is_null($locale)) {
            return app()->getLocale();
        }

        app()->setLocale($locale);

        return app()->getLocale();
    }
}

if (! function_exists('is_module_enabled')) {
    function is_module_enabled($module)
    {
        return array_key_exists($module, app('modules')->enabled());
    }
}

if (! function_exists('is_core_module')) {
    function is_core_module($module)
    {
        return in_array(strtolower($module), app('asgard.ModulesList'));
    }
}

if (! function_exists('asgard_i18n_editor')) {
    function asgard_i18n_editor($fieldName, $labelName, $content, $lang)
    {
        return view('core::components.i18n.textarea-wrapper', compact('fieldName', 'labelName', 'content', 'lang'));
    }
}

if (! function_exists('asgard_editor')) {
    function asgard_editor($fieldName, $labelName, $content)
    {
        return view('core::components.textarea-wrapper', compact('fieldName', 'labelName', 'content'));
    }
}

if (!function_exists('generate_datatable')){
    function generate_datatable($headers,$datas){
        $html = '<div class="table-responsive"><table class="data-table table table-bordered table-hover"><thead><tr>';

        foreach ($headers as $key => $header){
            $html .='<th ';
            if($key == 'actions'){
                $html .='data-sortable="false" ';
            }
            $html .='>'.$header.'</th>';
        }

        $html .='</tr></thead><tbody>';

        foreach ($datas as $data){
            $html .= '<tr>';
            foreach ($headers as $key => $header) {
                if(isset($data[$key])){
                    $html .= '<td>'.$data[$key].'</td>';
                } else {
                    $html .= '<td></td>';
                }
            }
            $html .= '</tr>';
        }

        $html .='</tbody></table></div>';

        return $html;
    }
}