<?php
    namespace MailingTest;

    use Mailing\Service;
    use Mailing\Config;
    use PHPUnit_Framework_TestCase as PHPUnitTestCase;
    use Zend\Mail as ZendMail;
    use Zend\View\Renderer\PhpRenderer;
    use Zend\View\Resolver\TemplatePathStack;
    
    class ServiceTest extends PHPUnitTestCase
    {
        public function testRendersEmptyWithNoTemplates()
        {
            $config = array(
                'mails' => array(
                    'test' => array()
                )
            );
            
            $service = $this->getService($config);
            $this->assertEmpty($service->renderMessage('test', Config::TYPE_PLAIN, array()));
        }
        
        
        public function testRendersEmpty()
        {
            $config = array(
                'mails' => array(
                    'test' => array(
                        'template' => array(
                            'html' => 'empty',
                            'plain' => 'empty'
                        )
                    )
                )
            );
            
            $service = $this->getService($config);
            $service->setRenderer($this->getTestRenderer());
            
            $this->assertEmpty($service->renderMessage('test', Config::TYPE_HTML, array()));
            $this->assertEmpty($service->renderMessage('test', Config::TYPE_PLAIN, array()));
        }
        
        public function testRendersEmptyWithLayout()
        {
            $config = array(
                'layouts' => array(
                    'test-layout' => array(
                        'plain' => 'plain-layout'
                    )
                ),
                'mails' => array(
                    'test' => array(
                        'template' => array(
                            'plain' => 'empty'
                        ),
                        'layout' => 'test-layout'
                    )
                )
            );
            
            $service = $this->getService($config);
            $service->setRenderer($this->getTestRenderer());
            
            $this->assertEquals('plain-layout', $service->renderMessage('test', Config::TYPE_PLAIN, array()));
        }
        
        public function testRendersWithLayout()
        {
            $config = array(
                'layouts' => array(
                    'test-layout' => array(
                        'plain' => 'plain-layout'
                    )
                ),
                'mails' => array(
                    'test' => array(
                        'template' => array(
                            'plain' => 'plain'
                        ),
                        'layout' => 'test-layout'
                    )
                )
            );
            
            $service = $this->getService($config);
            $service->setRenderer($this->getTestRenderer());
            
            $this->assertEquals('plain-layoutplain', $service->renderMessage('test', Config::TYPE_PLAIN, array()));
        }

        public function testSendsMailWithBothPlainAndHtml()
        {
            $config = array(
                'from' => array(
                    'default' => array(
                        'email' => 'test@test.com'
                    )
                ),
                'mails' => array(
                    'test' => array(
                        'template' => array(
                            'plain' => 'plain',
                            'html' => 'html'
                        )
                    )
                )
            );

            $service = $this->getService($config);
            $service->setRenderer($this->getTestRenderer());

            $result = $service->sendMail(new ZendMail\Message(), 'test');
            $parts = $result->getBody()->getParts();

            $this->assertCount(2, $parts);
            $this->assertEquals('text/plain', $parts[0]->type);
            $this->assertEquals('text/html', $parts[1]->type);
            $this->assertEquals('plain', $parts[0]->getContent());
            $this->assertEquals('html', $parts[1]->getContent());
        }

        public function testMessageIsSend()
        {
            $config = array(
                'from' => array(
                    'default' => array(
                        'email' => 'test@test.com'
                    )
                ),
                'mails' => array(
                    'test' => array(
                    )
                )
            );

            $service = $this->getService($config);
            $transportMock = $this->getMock('Zend\Mail\Transport\TransportInterface');
            $transportMock
                ->expects($this->once())
                ->method('send');
            $service->setTransport($transportMock);

            $service->sendMail(new ZendMail\Message(), 'test');
        }

        /**
         * @return PhpRenderer
         */
        public function getTestRenderer()
        {
            $resolver = new TemplatePathStack();
            $resolver->addPath(__DIR__ . '/../views');
            
            $renderer = new PhpRenderer();
            $renderer->setResolver($resolver);
            
            return $renderer;
        }
        
        /**
         * @param array $config
         * @return Service
         */
        public function getService(array $config)
        {
            $rendererMock = $this->getMock('Zend\View\Renderer\RendererInterface');
            $service = new Service(new Config($config), $rendererMock);
            
            $transportMock = $this->getMock('Zend\Mail\Transport\TransportInterface');
            $service->setTransport($transportMock);
            
            return $service;    
        }
    }
