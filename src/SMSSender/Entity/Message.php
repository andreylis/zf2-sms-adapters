<?php
/**
 * @author     Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="SMSSender\Repository\MessageRepository")
 * @ORM\Table(name="sms")
 */
class Message implements MessageInterface
{

    CONST STATUS_NEW = 0;
    CONST STATUS_SENT = 1;
    CONST STATUS_FAILED = 2;
	CONST STATUS_PRIORITIZED = 4;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     * @var string
     */
    protected $date;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $recipient;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $message;

    /**
     * @ORM\Column(type="smallint")
     * @var int STATUS_NEW|STATUS_SENT|STATUS_FAILED
     */
    protected $status;


    public function __construct()
    {
        $this->setDate(time());
        $this->setStatus(self::STATUS_NEW);
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function setSent()
    {
        $this->setStatus(self::STATUS_SENT);
    }

    public function setFailed()
    {
        $this->setStatus(self::STATUS_FAILED);
    }

    /**
     * @param string $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = preg_replace('/[^0-9]/', '', $recipient); // international number without pluses
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

	/**
	 * @return bool
	 */
	public function isPrioritized() {
		return $this->getStatus() == self::STATUS_PRIORITIZED;
	}


}