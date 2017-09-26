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
     * @var BuildStringParams
     */
    protected $buildStringParams;

    /**
     * @param ParameterizeTranslator $translator
     * @param BuildStringParams      $buildStringParams
     */
    public function __construct(
        ParameterizeTranslator $translator,
        BuildStringParams $buildStringParams
    ) {
        $this->translator = $translator;
        $this->buildStringParams = $buildStringParams;
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
    ):string {
        $params = $this->buildStringParams->__invoke(
            OptionsTranslate::getOption(
                $options,
                OptionsTranslate::OPTIONS_PARAMS,
                []
            )
        );

        return $this->translator->translate(
            $message,
            $params,
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
