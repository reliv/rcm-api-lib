<?php

namespace Reliv\RcmApiLib\Api\Translate;

use Zend\I18n\Translator\TranslatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class TranslateZf2 implements Translate
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    /**
     * @param string $message
     * @param array  $options
     *
     * @return mixed
     */
    public function __invoke(
        string $message,
        array $options = []
    ):string
    {
        return $this->translator->translate(
            $message,
            OptionsTranslate::getOption(
                $options,
                OptionsTranslate::OPTIONS_TEXT_DOMAIN,
                'default'
            ),
            OptionsTranslate::getOption(
                $options,
                OptionsTranslate::OPTIONS_LOCALE,
                null
            )
        );
    }
}
