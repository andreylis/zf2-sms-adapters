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
     * @param MessageInterface|Message $message
     * @return mixed|void
     * @throws RuntimeException
     */
    public function send(MessageInterface $message)
    {
        $config = $this->getSenderOptions();

        $serviceURL = "http://letsads.com/api";

        $body = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><request></request>');

        $auth = $body->addChild('auth');
        $auth->addChild('login', $config->getUsername());
        $auth->addChild('password', $config->getPassword());

        $messageXML = $body->addChild('message');
        $messageXML->addChild('from', $config->getSender());
        $messageXML->addChild('text', $message->getMessage());
        $messageXML->addChild('recipient', $message->getRecipient());

        $client = new Client();
        $client->setMethod(Request::METHOD_POST);
        $client->setUri($serviceURL);
        $client->setRawBody($body->asXML());
        $client->setOptions([
            'sslverifypeer' => false,
            'sslallowselfsigned' => true
        ]);

        try {
            $response = $client->send();
        } catch (Client\Exception\RuntimeException $e) {
            throw new RuntimeException("Failed to send sms", null, $e);
        }

        try {
            $responseXML = new \SimpleXMLElement($response->getBody());
        } catch (\Exception $e) {
            throw new RuntimeException("Cannot parse response", null, $e);
        }

        if ($responseXML->name === 'error') {
            throw new RuntimeException("LetsAds return error (" . $responseXML->description . ')');
        }
    }

}