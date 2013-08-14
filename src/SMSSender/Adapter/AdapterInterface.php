<?php
/**
 * @author     Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender\Adapter;

use SMSSender\Entity\MessageInterface;

interface AdapterInterface
{

    /**
     * @param MessageInterface $message
     * @return mixed
     */
    public function send(MessageInterface $message);

}