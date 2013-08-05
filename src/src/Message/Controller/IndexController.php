<?php
/**
 * @author     Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController  extends AbstractActionController
{

    protected $messageService;
    protected $entityManager;

    public function sendAction()
    {
        $this->getMessageService()->processUnprocessed();
    }

    /**
     * @return \SMSSender\Repository\MessageRepository
     */
    protected function getMessageRepository()
    {
        return $this->getEntityManager()->getRepository('SMSSender\Entity\SMSSender');
    }

    /**
     * @return \SMSSender\Service\MessageService
     */
    protected function getMessageService()
    {
        if (!$this->messageService) {
            $this->messageService = $this->getServiceLocator()->get('MessageService');
        }

        return $this->messageService;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('entity_manager');
        }

        return $this->entityManager;
    }



}