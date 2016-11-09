<?php
/**
 * reliv.rcm-api-lib.php
 */
return [
    /**
     * CompositeApiMessageHydrators
     * ['{HydratorServiceName}' => {priority}]
     */
    'CompositeApiMessageHydrators' => [
        'Reliv\RcmApiLib\Hydrator\ApiMessageApiMessagesHydrator' => 5000,
        'Reliv\RcmApiLib\Hydrator\ArrayApiMessagesHydrator' => 4000,
        'Reliv\RcmApiLib\Hydrator\ExceptionApiMessagesHydrator' => 3000,
        'Reliv\RcmApiLib\Hydrator\InputFilterApiMessagesHydrator' => 2000,
        'Reliv\RcmApiLib\Hydrator\StringApiMessagesHydrator' => 1000,
    ],

    /**
     * InputFilterApiMessagesHydrator
     */
    'InputFilterApiMessagesHydrator' => [
        'primaryMessage' => 'Some information was missing or invalid',
    ],
];
