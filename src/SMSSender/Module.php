<?php
/**
 * @author Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use SMSSender\Service\Options;
use Zend\Console\Adapter\AdapterInterface;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

class Module implements ConsoleUsageProviderInterface, BootstrapListenerInterface
{

    /**
     * @param EventInterface|MvcEvent $e
     * @return void
     */
    public function onBootstrap(EventInterface $e)
    {
        $app     = $e->getParam('application');
        /** @var ServiceManager $sm */
        $sm      = $app->getServiceManager();
        /** @var Options $options */
        $options = $sm->get('SMSSenderOptions');

        // Add the default entity driver only if specified in configuration
        if ($options->getEnableEntity()) {
            $chain = $sm->get('doctrine.driver.orm_default');

            $driver = new AnnotationDriver(new AnnotationReader());
            $driver->addPaths(array(__DIR__ . "/Entity"));

            $chain->addDriver($driver, 'SMSSender\Entity');
        }
    }

    /**
     * @return array|mixed|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
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
            'smssender send' => 'Send messages from queue'
        ];
    }

}
