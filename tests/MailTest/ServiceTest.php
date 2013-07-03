<?php
    namespace MailTest;

    use Mail\Service;
    use Mail\Config;
    use PHPUnit_Framework_TestCase as PHPUnitTestCase;
    use Zend\Mail as ZendMail;
    
    class ServiceTest extends PHPUnitTestCase
    {
        public function testRendersEmpty()
        {
            $config = array(
                'from' => array(
                    'default' => array(
                        'email' => 'test@test.com'
                    )
                ),
                'mails' => array(
                    'test' => array()
                )
            );
            
            $service = $this->getService($config);
            $this->assertEmpty($service->renderMessage('test', Config::TYPE_PLAIN, array()));
        }
        
        public function getService(array $config)
        {
            $service = new Service(new Config($config));
            
            $rendererMock = $this->getMock('Zend\View\Renderer\RendererInterface');
            $service->setRenderer($rendererMock);
            
            $transportMock = $this->getMock('Zend\Mail\Transport\TransportInterface');
            $service->setTransport($transportMock);
            
            return $service;    
        }
    }
