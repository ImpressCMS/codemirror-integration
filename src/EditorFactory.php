<?php

namespace ImpressCMS\Modules\CodeMirrorIntegration;

use Imponeer\Contracts\Editor\Adapter\EditorAdapterInterface;
use Imponeer\Contracts\Editor\Exceptions\IncompatibleEditorException;
use Imponeer\Contracts\Editor\Factory\EditorFactoryInterface;
use Imponeer\Contracts\Editor\Info\EditorInfoInterface;
use Imponeer\Contracts\Editor\Info\SourceEditorInfoInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Creates instances for editors
 *
 * @package ImpressCMS\Modules\CodeMirrorIntegration
 */
class EditorFactory implements EditorFactoryInterface
{
    /**
     * @var EditorInfo
     */
    protected $editorInfo;

    /**
     * EditorFactory constructor.
     *
     * @param EditorInfo $editorInfo
     */
    public function __construct(EditorInfo $editorInfo)
    {
        $this->editorInfo = $editorInfo;
    }

    /**
     * @inheritDoc
     */
    public function getInfo(): EditorInfoInterface
    {
        return $this->editorInfo;
    }

    /**
     * @inheritDoc
     */
    public function create(array $config, $checkCompatible = false): EditorAdapterInterface
    {
        $config['syntax'] = isset($config['syntax']) ? strtolower($config['syntax']) : null;

        if ($checkCompatible) {
            /**
             * @var SourceEditorInfoInterface $info
             */
            $info = $this->getInfo();
            if (!in_array($config['syntax'], $info->getSupportedLanguages(), true)) {
                throw new IncompatibleEditorException('Your selected syntax is unsupported');
            }
        }

        return new EditorAdapter($config);
    }
}