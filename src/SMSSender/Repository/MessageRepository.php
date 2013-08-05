<?php
/**
 * @author     Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender\Repository;

use Doctrine\ORM\EntityRepository;
use SMSSender\Entity\Message;

class MessageRepository extends EntityRepository {

    /**
     * @return Message
     */
    public function factorySMS()
    {
        return new Message();
    }

    public function sendMessage(Message $message)
    {
        $this->getEntityManager()->persist($message);
        $this->getEntityManager()->flush();
    }


    /**
     * @return Message[]
     */
    public function loadUnprocessed()
    {
        return $this->findBy(["status" => Message::STATUS_NEW], ['id' => 'asc'], 10);
    }

}