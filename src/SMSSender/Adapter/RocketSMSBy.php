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

class RocketSMSByAdapter implements AdapterInterface, ServiceLocatorAwareInterface
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

        $serviceURL = "http://api.rocketsms.by/simple/send?";

        $queryURL = $serviceURL . http_build_query([
                'username' => $config->getUsername(),
                'password' => $config->getPassword(),
                'phone' => $message->getRecipient(),
                'text' => $message->getMessage(),
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

        $responseData = @json_decode($response->getContent());

        if (empty($responseData) OR !isset($responseData->id)) {
            throw new RuntimeException("Failed to send sms");
        }


    }

}