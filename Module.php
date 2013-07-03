<?php
    namespace Mailing;

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
     * @package Mailing
     */
    class Module implements
        Feature\ConfigProviderInterface,
        Feature\ServiceProviderInterface
    {
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
