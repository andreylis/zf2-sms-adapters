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
use Zend\Http\Request;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class LetsAdsAdapter implements AdapterInterface, ServiceLocatorAwareInterface
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

        $serviceURL = "http://letsads.com/api";

        $xml = "<?" . "xml version='1.0' encoding='UTF-8'" . "?>
        <request>
            <auth>
                <login>{$config->getUsername()}</login>
                <password>{$config->getPassword()}</password>
            </auth>
            <message>
                <from>{$config->getSender()}</from>
                <text>{$message->getMessage()}</text>
                <recipient>{$message->getRecipient()}</recipient>
            </message>
        </request>";

        $client = new Client();
        $client->setMethod(Request::METHOD_POST);
        $client->setUri($serviceURL);
        $client->setRawBody($xml);
        $client->setOptions([
            'sslverifypeer' => false,
            'sslallowselfsigned' => true
        ]);

        try {
            $response = $client->send();

        } catch (Client\Exception\RuntimeException $e) {
            throw new RuntimeException("Failed to send sms", null, $e);
        }

        if (!$response OR strstr($response, "<name>Error</name>")) {
            throw new RuntimeException("Failed to send sms");
        }

    }

}