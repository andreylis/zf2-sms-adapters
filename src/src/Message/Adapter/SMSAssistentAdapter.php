<?php
/**
 * @author     Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender\Adapter;

use SMSSender\Adapter\AdapterInterface;
use SMSSender\Entity\Message;
use SMSSender\Module as Module;
use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;

class SMSAssistentAdapter implements AdapterInterface
{

    /**
     * @param Message $message
     * @return Message
     */
    public function send(Message $message)
    {
        $moduleObject=  new Module();
        $config = $moduleObject->getConfig();
        $smsAssistentConfig = $config['sms'];

        $serviceURL = "https://sys.sms-assistent.ru/api/v1/send_sms/plain?";

        $queryURL = $serviceURL . http_build_query([
                'user' => $smsAssistentConfig['username'],
                'password' => $smsAssistentConfig['password'],
                'recipient' => str_replace(["+", " ", '-'], "", $message->getRecipient()), // на всякий случай
                'message' => $message->getMessage(),
                'sender' => $smsAssistentConfig['sender'],
            ]);

        $client =  new Client();
        $client->setAdapter((new Curl()));
        $client->setUri($queryURL);

        $response = $client->send();

        if (floatval($response->getBody()) > 0) {
            $message->setSent();
        } else {
            $message->setFailed();
        }

        return $message;

    }

}