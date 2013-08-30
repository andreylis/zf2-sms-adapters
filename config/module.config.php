<?php

namespace SMSSender;


return array(

    'sms' => [

        'provider' => 'SMSSenderAssistentAdapter', /** or any other supported provider */
        'username' => '',
        'password' => '',
        'sender'   => 'ZF2',/** this value must be approved by provider */

    ],

    'console' => array(
        'router' => array(
            'routes' => array(
                'send-messages' => array(
                    'options' => array(
                        'route' => 'smssender send',
                        'defaults' => array(
                            'controller' => 'SMSSender-index',
                            'action' => 'send'
                        )
                    )
                ))
        )
    ),
    'controllers' => [
        'invokables' => [
            'SMSSender-index' => 'SMSSender\Controller\IndexController'
        ]
    ],
    'service_manager' => [
        'factories' => [
            'SMSSenderOptions' => 'SMSSender\Service\OptionsFactory',
            'SMSSenderService' => 'SMSSender\Service\SenderServiceFactory'

        ],
        'invokables' => [
            'SMSSenderAssistentAdapter' => 'SMSSender\Adapter\SMSAssistentAdapter',
            'SMSSenderSMSRuAdapter' => 'SMSSender\Adapter\SMSRuAdapter',
            'SMSSenderSMSCRuAdapter' => 'SMSSender\Adapter\SMSCRuAdapter'
        ]
    ]
);
