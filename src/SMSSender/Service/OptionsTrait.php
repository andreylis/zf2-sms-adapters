<?php
/**
 * @author     Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender\Service;

trait OptionsTrait
{

    /**
     * @var Options
     */
    protected $senderOptions;

    /**
     * @param \SMSSender\Service\Options $senderOptions
     */
    public function setSenderOptions($senderOptions)
    {
        $this->senderOptions = $senderOptions;
    }

    /**
     * @return \SMSSender\Service\Options
     */
    public function getSenderOptions()
    {
        if (!$this->senderOptions) {
            $this->setSenderOptions($this->getServiceLocator()->get("SMSSenderOptions"));
        }
        return $this->senderOptions;
    }


}