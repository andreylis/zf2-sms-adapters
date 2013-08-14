<?php
/**
 * @author     Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender\Entity;


interface MessageInterface
{

    public function getRecipient();

    public function getMessage();

    public function setFailed();

    public function setSent();

}