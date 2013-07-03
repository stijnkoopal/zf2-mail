<?php
    namespace Mailing;

    use Zend\ServiceManager\ServiceLocatorAwareInterface;

    /**
     * Class MailServiceProviderTrait
     * @package Mailing
     */
    trait MailServiceProviderTrait
    {
        /**
         * @var Service
         */
        protected $mailService;

        /**
         * @return Service
         */
        public function getMailService()
        {
            if (!$this->mailService && $this instanceof ServiceLocatorAwareInterface) {
                $this->mailService = $this->getServiceLocator()->get('Mail\Service');
            }
            return $this->mailService;
        }

        /**
         * @param Service $service
         * @return $this
         */
        public function setMailService(Service $service)
        {
            $this->mailService = $service;
            return $this;
        }
    }
