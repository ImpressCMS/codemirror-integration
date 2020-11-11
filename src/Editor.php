<?php

namespace ImpressCMS\Modules\CodeMirrorIntegration;

use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Defines CodeMirror editor
 *
 * @package ImpressCMS\Plugins\SourceEditors\CodeMirror
 */
class Editor implements EditorInterface
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
    public function getTitle(): string
    {
        return $this->translator->trans('_ICMS_SOURCEEDITOR_CODEMIRROR', [], 'editor');
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
    public function create(array $configs, $checkCompatible = false): \icms_form_elements_Textarea
    {
        return new TextAreaElement($configs, $checkCompatible);
    }

    /**
     * @inheritDoc
     */
    public function getOrder(): ?int
    {
        return 1;
    }

    /**
     * @inheritDoc
     */
    public function supportsHTML(): bool
    {
        return false;
    }
}