<?php
/**
 * @author     Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender\Controller;

use SMSSender\Service\SenderServiceTrait;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{

    use SenderServiceTrait;

    protected $entityManager;

    public function sendAction()
    {
        $this->getSenderService()->processUnprocessed();
        $this->getEntityManager()->flush();
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