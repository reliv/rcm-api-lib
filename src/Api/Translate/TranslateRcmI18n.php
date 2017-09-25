<?php

namespace Reliv\RcmApiLib\Api\Translate;

use RcmI18n\Service\ParameterizeTranslator;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class TranslateRcmI18n implements Translate
{
    /**
     * @var ParameterizeTranslator
     */
    protected $translator;

    /**
     * @param ParameterizeTranslator $translator
     */
    public function __construct(
        ParameterizeTranslator $translator
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
                OptionsTranslate::OPTIONS_PARAMS,
                []
            ),
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
