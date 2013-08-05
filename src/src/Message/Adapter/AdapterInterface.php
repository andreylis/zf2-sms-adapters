<?php
/**
 * @author     Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender\Adapter;

use SMSSender\Entity\Message;

interface AdapterInterface {

    /**
     * @param Message $message
     * @return mixed
     */
    public function send(Message $message);

}