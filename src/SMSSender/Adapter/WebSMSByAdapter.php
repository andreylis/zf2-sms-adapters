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

class WebSMSByAdapter implements AdapterInterface, ServiceLocatorAwareInterface
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

        $serviceURL = "http://websms.by";

        $queryURL = $serviceURL . http_build_query([
                'r' => 'api/msg_send',
                'user' => $config->getUsername(),
                'apikey' => $config->getPassword(),
                'recipients' => $message->getRecipient(),
                'sender' => $config->getSender(),
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


        if ($response) {
            $responseData = @json_decode($response->getContent());

            if (empty($responseData) OR $responseData->status != "success") {
                if (isset($responseData->message)) {
                    throw new RuntimeException("Failed to send sms: " . $responseData->message);
                } else {
                    throw new RuntimeException("Failed to send sms");
                }
            }
        }


    }

}