<?php

namespace Modules\Core\Events;

use Modules\Core\Foundation\Asset\Pipeline\AssetPipeline;

class EditorIsRendering
{
    /**
     * @var AssetPipeline
     */
    private $assetPipeline;
    private $editorClass;
    private $editorJsPartial;
    private $editorCssPartial;
    private $editorComponents = [
        'i18n' => 'core::components.i18n.textarea',
        'normal' => 'core::components.textarea',
    ];

    public function __construct(AssetPipeline $assetPipeline)
    {
        $this->assetPipeline = $assetPipeline;
    }

    public function addJs($asset)
    {
        $this->assetPipeline->requireJs($asset);

        return $this;
    }

    public function addCss($asset)
    {
        $this->assetPipeline->requireCss($asset);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEditorClass()
    {
        return $this->editorClass;
    }

    /**
     * @param mixed $editorClass
     */
    public function setEditorClass($editorClass)
    {
        $this->editorClass = $editorClass;
    }

    /**
     * @return mixed
     */
    public function getEditorJsPartial()
    {
        return $this->editorJsPartial;
    }

    /**
     * @param mixed $editorJsPartial
     */
    public function setEditorJsPartial($editorJsPartial)
    {
        $this->editorJsPartial = $editorJsPartial;
    }

    /**
     * @return mixed
     */
    public function getEditorCssPartial()
    {
        return $this->editorCssPartial;
    }

    /**
     * @param mixed $editorCssPartial
     */
    public function setEditorCssPartial($editorCssPartial)
    {
        $this->editorCssPartial = $editorCssPartial;
    }

    public function getI18nComponentName()
    {
        return $this->editorComponents['i18n'];
    }

    public function setI18nComponentName($componentName)
    {
        $this->editorComponents['i18n'] = $componentName;
    }

    public function getComponentName()
    {
        return $this->editorComponents['normal'];
    }

    public function setComponentName($componentName)
    {
        $this->editorComponents['normal'] = $componentName;
    }
}
