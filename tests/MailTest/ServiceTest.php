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
            $config = [
                'from' => [
                    'default' => [
                        'email' => 'test@test.com'
                    ]
                ],
                'mails' => [
                    'test' => []
                ]
            ];
            $service = $this->getService($config);
            
            $message = $service->sendMail(new ZendMail\Message(), 'test'); 
            
            $this->assertCount(1, $message->getBody()->getParts());
            $this->assertEmpty($message->getBody()->getParts()[0]->getContent());
            $this->assertEquals('text/plain', $message->getBody()->getParts()[0]->type);
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
