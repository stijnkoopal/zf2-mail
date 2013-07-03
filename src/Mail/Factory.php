<?php
    namespace Mail;

    use Zend\ServiceManager\FactoryInterface;
    use Zend\ServiceManager\ServiceLocatorInterface;

    /**
     * Class Factory
     * @package Mail\Service
     */
    class Factory implements FactoryInterface
    {
        /**
         * @param ServiceLocatorInterface $locator
         * @return Service
         */
        public function createService(ServiceLocatorInterface $locator)
        {
            $config = $locator->get('config');
            $emailConfig = $config['mail'];

            $renderer = $locator->get('viewrenderer');
            return new Service(new Config($emailConfig), $renderer);
        }
    }
