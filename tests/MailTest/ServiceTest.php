<?php
    namespace MailTest;

    use Mail\Service;
    use Mail\Config;
    use PHPUnit_Framework_TestCase as PHPUnitTestCase;
    use Zend\Mail as ZendMail;
    use Zend\View\Renderer\PhpRenderer;
    use Zend\View\Resolver\TemplatePathStack;
    
    class ServiceTest extends PHPUnitTestCase
    {
        public function testRendersEmptyWithNoTemplates()
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
        
        
        public function testRendersEmpty()
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
        
        public function testRendersWithLayout()
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
                            'plain' => 'empty'
                        )
                    )
                )
            );
            
            $service = $this->getService($config);
            $service->setRenderer($this->getTestRenderer());
            
            $this->assertEmpty($service->renderMessage('test', Config::TYPE_PLAIN, array()));
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
