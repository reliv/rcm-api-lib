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
     * @var BuildStringParams
     */
    protected $buildStringParams;

    /**
     * @param TranslatorInterface $translator
     * @param BuildStringParams   $buildStringParams
     */
    public function __construct(
        TranslatorInterface $translator,
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
    ):string
    {
        $params = $this->buildStringParams->__invoke(
            OptionsTranslate::getOption(
                $options,
                OptionsTranslate::OPTIONS_PARAMS,
                []
            )
        );

        $message = $this->prepareMessage(
            $message,
            $params
        );

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

    /**
     * @param string $message
     * @param array  $params
     *
     * @return string
     */
    protected function prepareMessage(string $message, array $params)
    {
        foreach ($params as $name => $value) {
            $message = str_replace(
                '{' . $name . '}',
                $value,
                $message
            );
        }

        return $message;
    }
}
