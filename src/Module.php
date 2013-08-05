<?php
/**
 * @author Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender;

use SMSSender\Service\MessageService;
use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\ServiceManager;


class Module implements ServiceProviderInterface, ConsoleUsageProviderInterface
{

    /**
     * @return array|mixed|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . "/autoload_classmap.php"
            ),
        );
    }

    /**
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'MessageService' => function (ServiceManager $sm) {
                    return new MessageService(
                        $sm
                    );
                }
            )
        );
    }

    /**
     * @param AdapterInterface $console
     * @return array|string|null
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return [
            'send messages' => 'Send messages from queue'
        ];
    }

}
