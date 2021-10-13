<?php

namespace ImpressCMS\Modules\CodeMirrorIntegration;

use Imponeer\Contracts\Editor\Adapter\EditorAdapterInterface;
use JsonException;

/**
 * Describes editor adapter instance
 *
 * @package ImpressCMS\Modules\CodeMirrorIntegration
 */
class EditorAdapter implements EditorAdapterInterface
{
    /**
     * Editor width
     *
     * @var string
     */
    private $width = "100%";

    /**
     * Editor height
     *
     * @var string
     */
    private $height = "400px";

    /**
     * Syntax variant to use
     *
     * @var string
     */
    private $syntax = 'php';

    /**
     * Is in readonly state?
     *
     * @var bool
     */
    private $readonly;

    /**
     * Target element selector where this editor will be applied
     *
     * @var string
     */
    private $targetSelector;

    /**
     * Public url to assets
     *
     * @var string
     */
    private $publicAssetsUrl;

    /**
     * EditorAdapter constructor.
     *
     * @param array $config Editor config
     */
    public function __construct(array $config)
    {
        if (isset($config['width'])) {
            $this->width = $config['width'];
        }

        if (isset($config['height'])) {
            $this->height = $config['height'];
        }

        if (isset($config['syntax'])) {
            $this->syntax = $this->resolveSyntaxFromValue($config['syntax']);
        }

        $this->readonly = isset($config["is_editable"]) ? !$config["is_editable"] : false;
        $this->targetSelector = $config['target_selector'];
        $this->publicAssetsUrl = $config['public_assets_url'] . 'vendor/npm-asset/codemirror/';
    }

    /**
     * Resolves syntax name from value
     *
     * @param string $value Value from where to resolve true syntax
     *
     * @return string
     */
    protected function resolveSyntaxFromValue(string $value): string
    {
        $value = strtolower($value);
        switch ($value) {
            case 'html':
            case 'htm':
                return 'xml';
            case 'javascript':
                return 'js';
            case 'mixed':
                return 'htmlmixed';
            default:
                return $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return [
            'style' => "width: {$this->width}; height: {$this->height}",
        ];
    }

    /**
     * @inheritDoc
     */
    public function getStyleURLs(): array
    {
        return [
            $this->publicAssetsUrl . 'lib/codemirror.css',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getScriptURLs(): array
    {
        $scripts = [
            $this->publicAssetsUrl . 'lib/codemirror.js',
        ];
        if ($this->syntax === 'htmlmixed') {
            $scripts[] = $this->publicAssetsUrl . 'mode/xml/xml.js';
            $scripts[] = $this->publicAssetsUrl . 'mode/javascript/javascript.js';
            $scripts[] = $this->publicAssetsUrl . 'mode/css/css.js';
            $scripts[] = $this->publicAssetsUrl . 'mode/htmlmixed/htmlmixed.js';
        } else {
            $scripts[] = $this->publicAssetsUrl . 'mode/' . $this->syntax . '/' . $this->syntax . '.js';
        }
        return $scripts;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function getScriptCode(): string
    {
        $config = json_encode([
            'width' => $this->width,
            'height' => $this->height,
            'lineNumbers' => true,
            'continuousScanning' => 500,
            'textWrapping' => false,
            'readOnly' => $this->readonly,
            'mode' => $this->syntax,
        ], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);

        $selectorHash = sha1($this->targetSelector);

        return "var editor_{$selectorHash} = CodeMirror.fromTextArea(document.querySelector('{$this->targetSelector}'), {$config});";
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        $ret = '';
        foreach ($this->getStyleURLs() as $style) {
            $ret .= '<link rel="stylesheet" href="'.htmlentities($style).'" type="text/css" media="all" />';
        }
        foreach ($this->getScriptURLs() as $script) {
            $ret .= '<script src="' . htmlentities($script) . '"></script>';
        }
        $ret .= '<script>' . $this->getScriptCode() . '</script>';

        return $ret;
    }
}