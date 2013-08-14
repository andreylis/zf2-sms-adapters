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

class SMSCRuAdapter implements AdapterInterface, ServiceLocatorAwareInterface
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

        $serviceURL = "http://sms.ru/sms/send?";

        $queryURL = $serviceURL . http_build_query([
                'login' => $config->getUsername(),
                'password' => $config->getPassword(),
                'to' => str_replace(["+", " ", '-'], "", $message->getRecipient()), // на всякий случай
                'from' => $config->getSender(),
                'text' => $message->getMessage(),
                'partner_id' => 21871 // please, left this as is
            ]);

        $client =  new Client();
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

        if (empty($responseData) OR trim(current(explode("\n", $responseData))) !== "100") {
            throw new RuntimeException("Failed to send sms");
        }

    }

}