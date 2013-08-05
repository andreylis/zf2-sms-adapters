<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace SMSSender\Service;

use Doctrine\ORM\EntityManager;
use SMSSender\Entity\Message;
use SMSSender\Exception\RuntimeException;
use SMSSender\Repository\MessageRepository;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class SenderService implements  ServiceLocatorAwareInterface
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
     */
    public function sendSMS($phone, $text)
    {
        $message = $this->getMessageRepository()->factorySMS();
        $message->setMessage($text);
        $message->setRecipient($phone);
        $this->getMessageRepository()->sendMessage($message);
    }


    public function processUnprocessed()
    {

        $adapter = $this->getServiceLocator()->get($this->getSenderOptions()->getProvider());

        foreach ($this->getMessageRepository()->loadUnprocessed() as $message) {
            try {
                $adapter->send($message);
            } catch (RuntimeException $e) {
            }

            $this->getEntityManager()->persist($message);
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
