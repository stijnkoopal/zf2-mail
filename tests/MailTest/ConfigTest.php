<?php
    namespace MailTest;

    use Mail\Config;
    use PHPUnit_Framework_TestCase as PHPUnitTestCase;

    class ConfigTest extends PHPUnitTestCase
    {
        public function testGetLayoutTemplateReturnsDefault()
        {
            $config = new Config(array(
                'layouts' => array(
                    'default' => array(
                        'plain' => 'plain-template',
                        'html' => 'html-template'
                    )
                ),
                'mails' => array(
                    'test' => array(
                    )
                )
            ));

            $this->assertEquals('plain-template', $config->getLayoutTemplate('test', 'plain'));
            $this->assertEquals('html-template', $config->getLayoutTemplate('test', 'html'));
        }

        public function testGetLayoutTemplateGivesRightTemplate()
        {
            $config = new Config(array(
                'layouts' => array(
                    'template1' => array(
                        'plain' => 'plain-template1',
                        'html' => 'html-template1'
                    ),
                    'template2' => array(
                        'plain' => 'plain-template2',
                        'html' => 'html-template2'
                    ),
                ),
                'mails' => array(
                    'test1' => array(
                        'layout' => 'template1'
                    ),
                    'test2' => array(
                        'layout' => 'template2'
                    )
                )
            ));

            $this->assertEquals('plain-template1', $config->getLayoutTemplate('test1', 'plain'));
            $this->assertEquals('html-template1', $config->getLayoutTemplate('test1', 'html'));

            $this->assertEquals('plain-template2', $config->getLayoutTemplate('test2', 'plain'));
            $this->assertEquals('html-template2', $config->getLayoutTemplate('test2', 'html'));
        }

        public function testGetTemplateIsCorrect()
        {
            $config = new Config(array(
                'mails' => array(
                    'test1' => array(
                        'template' => array(
                            'plain' => 'template1-plain',
                            'html' => 'template1-html'
                        )
                    ),
                    'test2' => array(
                        'template' => array(
                            'plain' => 'template2-plain',
                            'html' => 'template2-html'
                        ),
                    )
                )
            ));

            $this->assertEquals('template1-plain', $config->getTemplate('test1', 'plain'));
            $this->assertEquals('template1-html', $config->getTemplate('test1', 'html'));

            $this->assertEquals('template2-plain', $config->getTemplate('test2', 'plain'));
            $this->assertEquals('template2-html', $config->getTemplate('test2', 'html'));
        }
        
        /**
         * @expectedException Mail\Exception\InvalidArgumentException
         */
        public function testEmailAliasIsDefined()
        {
            $config = new Config(array());
            
            $this->assertNull($config->getTemplate('test3', 'plain'));
        }
        
        public function testSubjectIsReturnedAboveTemplate()
        {
            $config = new Config(array(
                'mails' => array(
                    'test' => array(
                        'template' => array(
                            'subject' => 'test-subject'
                        ),
                        'subject' => 'right-subject'
                    )
                )
            ));
            
            $this->assertEquals('right-subject', $config->getSubject('test'));
        }
        
        public function testSubjectTemplateIsCorrect()
        {
            $config = new Config(array(
                'mails' => array(
                    'test' => array(
                        'template' => array(
                            'subject' => 'right-subject'
                        )
                    )
                )
            ));
            
            $this->assertEquals('right-subject', $config->getSubjectTemplate('test'));
        }
       
        public function testNoSubjectTemplateIsReturned()
        {
            $config = new Config(array(
                'mails' => array(
                    'test' => array(
                    )
                )
            ));
            
            $this->assertNull($config->getSubjectTemplate('test'));
       }
           
       public function testFromHasCorrectEmailWithNoDomain()
       {
            $config = new Config(array(
                'domains' => array(
                    'default' => 'bla-domain.com'
                ),
                'from' => array(
                    'noreply' => array(
                        'email' => 'noreply',
                        'name' => 'noreply-name'
                    )
                ),
                'mails' => array(
                    'test' => array(
                        'from' => 'noreply'
                    )
                )
            ));
            
            $from = $config->getFrom('test');
            $this->assertEquals('noreply@bla-domain.com', $from['email']);
            $this->assertEquals('noreply-name', $from['name']);
        }
          
        public function testFromHasCorrectEmailWithDomain()
        {
            $config = new Config(array(
                'domains' => array(
                    'default' => 'bla-domain.com',
                    'domain' => 'my-domain.nl'
                ),
                'from' => array(
                    'noreply' => array(
                        'email' => 'noreply',
                        'name' => 'noreply-name',
                        'domain' => 'domain'
                    )
                ),
                'mails' => array(
                    'test' => array(
                        'from' => 'noreply'
                    )
                )
            ));
            
            $from = $config->getFrom('test');
            
            $this->assertEquals('noreply@my-domain.nl', $from['email']);
            $this->assertEquals('noreply-name', $from['name']);
        }
    }
