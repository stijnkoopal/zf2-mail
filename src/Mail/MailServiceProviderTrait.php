<?php
    namespace Mail;

    use Zend\ServiceManager\ServiceLocatorAwareInterface;

    /**
     * Class MailServiceProviderTrait
     * @package Mail
     */
    trait MailServiceProviderTrait
    {
        /**
         * @var MailService
         */
        protected $mailService;

        /**
         * @return MailService
         */
        public function getMailService()
        {
            if (!$this->mailService && $this instanceof ServiceLocatorAwareInterface) {
                $this->mailService = $this->getServiceLocator()->get('Mail\Service');
            }
            return $this->mailService;
        }

        /**
         * @param MailService $service
         * @return $this
         */
        public function setMailService(MailService $service)
        {
            $this->mailService = $service;
            return $this;
        }
    }
