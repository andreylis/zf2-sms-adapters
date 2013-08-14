<?php
/**
 * @author     Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender\Adapter;

use SMSSender\Adapter\AdapterInterface;
use SMSSender\Entity\Message;
use SMSSender\Entity\MessageInterface;
use SMSSender\Exception\RuntimeException;
use SMSSender\Service\OptionsTrait;
use Zend\Http\Client;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class SMSAssistentAdapter implements AdapterInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait, OptionsTrait;

    /**
     * @param MessageInterface $message
     * @return mixed|void
     * @throws RuntimeException
     */
    public function send(MessageInterface $message)
    {
        $config = $this->getSenderOptions();

        $serviceURL = "https://sys.sms-assistent.ru/api/v1/send_sms/plain?";

        $queryURL = $serviceURL . http_build_query([
                'user' => $config->getUsername(),
                'password' => $config->getPassword(),
                'sender' => $config->getSender(),
                'recipient' => $message->getRecipient(),
                'message' => $message->getMessage(),
            ]);

        $client = new Client();
        $client->setUri($queryURL);
        $client->setOptions([
            'sslverifypeer' => false,
            'sslallowselfsigned' => true
        ]);

        try {
            $response = $client->send();

        } catch (Client\Exception\RuntimeException $e) {
            throw new RuntimeException("Failed to send sms", null, $e);
        }

        if (floatval($response->getBody()) <= 0) {
            throw new RuntimeException("Failed to send sms");
        }

    }

}