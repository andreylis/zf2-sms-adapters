<?php
/**
 * @author Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender\Service;

use Doctrine\ORM\EntityManager;
use SMSSender\Entity\Message;
use SMSSender\Entity\MessageInterface;
use SMSSender\Exception\RuntimeException;
use SMSSender\Repository\MessageRepository;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class SenderService implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait, OptionsTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @param $phone string
     * @param $text string
     * @param bool $useStorage
     */
    public function sendSMS($phone, $text, $useStorage = true)
    {
        $message = new Message();
        $message->setMessage($text);
        $message->setRecipient($phone);

        if ($useStorage && $this->getSenderOptions()->getEnableEntity()) {
	        if ($message->isPrioritized()) {
		        $this->directSend($message);
	        }
            $this->getMessageRepository()->sendMessage($message);
        } else {
            $this->directSend($message);
        }
    }


    public function processUnprocessed()
    {
        $messages = $this->getMessageRepository()->loadUnprocessed();
        if (!empty($messages)) {
            foreach ($messages as $message) {
                $this->directSend($message);
                $this->getEntityManager()->persist($message);
            }
        }
    }

    public function directSend(MessageInterface $message)
    {
        $adapter = $this->getServiceLocator()->get($this->getSenderOptions()->getProvider());
        try {
            $adapter->send($message);
            $message->setSent();
        } catch (RuntimeException $e) {
            $message->setFailed();
        }
    }

    /**
     * @return MessageRepository
     */
    public function getMessageRepository()
    {
        return $this->getEntityManager()->getRepository('SMSSender\Entity\Message');
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->getServiceLocator()->get('entity_manager');
    }

}
