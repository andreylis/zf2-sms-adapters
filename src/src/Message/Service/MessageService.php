<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace SMSSender\Service;

use Doctrine\ORM\EntityManager;
use SMSSender\Adapter\SMSAssistentAdapter;
use SMSSender\Entity\Message;
use SMSSender\Repository\MessageRepository;
use Zend\ServiceManager\ServiceManager;
use SMSSender\Module as Module;

class MessageService
{


    /**
     * @var ServiceManager
     */
    protected $serviceManager;


    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @return \SMSSender\Service\MessageService
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
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

        $moduleObject=  new Module();
        $config = $moduleObject->getConfig()['sms'];

        switch ($config['provider']) {
            case "sms-assistent": $adapter = new SMSAssistentAdapter(); break;
            default: throw new \Exception("Wrong provider name");
        }

        /**
         * @var $message Message
         */
        foreach ($this->getMessageRepository()->loadUnprocessed() as $message) {;
            $adapter->send($message);
            $this->getEntityManager()->persist($message);
            $this->getEntityManager()->flush($message);
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
        return $this->serviceManager->get('entity_manager');
    }

}
