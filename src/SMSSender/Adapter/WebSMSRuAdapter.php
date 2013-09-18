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

class WebSMSRuAdapter implements AdapterInterface, ServiceLocatorAwareInterface
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

        $serviceURL = "http://www.websms.ru/http_in5.asp?";

        $queryURL = $serviceURL . http_build_query([
                'http_username' => $config->getUsername(),
                'http_password' => $config->getPassword(),
                'phone_list' => $message->getRecipient(),
                'fromPhone' => $config->getSender(),
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


        foreach (explode("\n", $response->getBody()) as $line) {
            $line = explode(" = ", $line);
            if (trim($line[0]) == "error_num" && trim($line[1]) != "OK") {
                throw new RuntimeException("Failed to send sms");
            }
        }


    }

}