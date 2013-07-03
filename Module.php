<?php
    namespace Mail;

    use Acl\Role\Administrator;
    use Acl\Role\Player;
    use SubAccount\Permission\Acl\Assert\SubAccountOwned\Assertion;
    use Zend\EventManager\EventInterface;
    use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
    use Zend\ModuleManager\Feature\ConfigProviderInterface;
    use Zend\ModuleManager\Feature\ServiceProviderInterface;
    use Zend\ModuleManager\Feature\BootstrapListenerInterface;
    use Zend\Loader;
    use Zend\ModuleManager\Feature;
    use Zend\ServiceManager\ServiceManager;

    /**
     * Class Module
     * @package Mail
     */
    class Module implements
        Feature\ConfigProviderInterface,
        Feature\AutoloaderProviderInterface,
        Feature\ServiceProviderInterface
    {
        /**
         * @return array
         */
        public function getAutoloaderConfig()
        {
            return array(
                'Zend\Loader\ClassMapAutoloader' => array(
                    __DIR__ . '/autoload_classmap.php',
                ),
                Loader\AutoloaderFactory::STANDARD_AUTOLOADER => array(
                    'namespaces' => array(
                        __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    ),
                ),
            );
        }

        /**
         * @return array|mixed|\Zend\ServiceManager\Config
         */
        public function getServiceConfig()
        {
            return include __DIR__ . '/config/service.config.php';
        }

        /**
         * @return array|mixed|\Traversable
         */
        public function getConfig()
        {
            return include __DIR__ . '/config/module.config.php';
        }
    }
