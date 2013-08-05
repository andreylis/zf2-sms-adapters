<?php
/**
 * @author     Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender\Adapter;

use SMSSender\Adapter\AdapterInterface;
use SMSSender\Entity\Message;
use SMSSender\Exception\RuntimeException;
use SMSSender\Service\OptionsTrait;
use Zend\Http\Client;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class SMSAssistentAdapter implements AdapterInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait, OptionsTrait;

    /**
     * @param Message $message
     * @throws RuntimeException
     * @return Message
     */
    public function send(Message $message)
    {
        $config = $this->getSenderOptions();

        $serviceURL = "https://sys.sms-assistent.ru/api/v1/send_sms/plain?";

        $queryURL = $serviceURL . http_build_query([
                'user' => $config->getUsername(),
                'password' => $config->getPassword(),
                'sender' => $config->getSender(),
                'recipient' => str_replace(["+", " ", '-'], "", $message->getRecipient()), // на всякий случай
                'message' => $message->getMessage(),
            ]);

        $client =  new Client();
        $client->setUri($queryURL);

        try {
            $response = $client->send();

            if (floatval($response->getBody()) > 0) {
                $message->setSent();
            } else {
                $message->setFailed();
            }

        } catch (Client\Exception\RuntimeException $e) {
            $message->setFailed();
            throw new RuntimeException("Failed to send sms with ID " . $message->getId(), null, $e);
        }

        return $message;

    }

}