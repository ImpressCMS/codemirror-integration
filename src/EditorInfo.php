<?php

namespace ImpressCMS\Modules\CodeMirrorIntegration;

use Imponeer\Contracts\Editor\Info\SourceEditorInfoInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Gives a bit info about editor
 *
 * @package ImpressCMS\Modules\CodeMirrorIntegration
 */
class EditorInfo implements SourceEditorInfoInterface
{

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Editor constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->translator->trans('_ICMS_SOURCEEDITOR_CODEMIRROR', [], 'editor');
    }

    /**
     * @inheritDoc
     */
    public function isAvailable(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '0.0.0';
    }

    /**
     * @inheritDoc
     */
    public function getLicense(): string
    {
        return 'MIT';
    }

    /**
     * @inheritDoc
     */
    public function getSupportedLanguages(): array
    {
        return [
            'html',
            'xml',
            'php',
            'lua',
            'python',
            'javascript',
            'js',
            'css',
            'sparql',
            'mixed',
        ];
    }
}