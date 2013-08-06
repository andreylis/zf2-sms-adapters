<?php

namespace SMSSender;


return array(

    'sms' => [

        'provider'  => 'SMSSenderAssistentAdapter', /** 'sms-assistent' or any other supported provider */
        'username'  => '',
        'password'  => '',
        'sender'    => 'ZF2',           /** this value must be approved by sms-assistent */

    ],

    'console' => array(
        'router' => array(
            'routes' => array(
                'send-messages' => array(
                    'options' => array(
                        'route' => 'send messages',
                        'defaults' => array(
                            'controller' => 'message-index',
                            'action' => 'send'
                        )
                    )
                ))
        )
    ),
    'controllers' => [
        'invokables' => [
            'message-index' => 'SMSSender\Controller\IndexController'
        ]
    ],
    'service_manager' => [
        'factories' => [
            'SMSSenderOptions' => 'SMSSender\Service\OptionsFactory',
            'SMSSenderService' => 'SMSSender\Service\SenderServiceFactory'

        ],
        'invokables' => [
            'SMSSenderAssistentAdapter' => 'SMSSender\Adapter\SMSAssistentAdapter'
        ]
    ]
);
