<?php
    namespace MailTest;

    use Mail\Config;
    use PHPUnit_Framework_TestCase as PHPUnitTestCase;

    class ConfigTest extends PHPUnitTestCase
    {
        public function testGetLayoutTemplateReturnsDefault()
        {
            $config = new Config([
                'layouts' => [
                    'default' => [
                        'plain' => 'plain-template',
                        'html' => 'html-template'
                    ]
                ],
                'mails' => [
                    'test' => [

                    ]
                ]
            ]);

            $this->assertEquals('plain-template', $config->getLayoutTemplate('test', 'plain'));
            $this->assertEquals('html-template', $config->getLayoutTemplate('test', 'html'));
        }

        public function testGetLayoutTemplateGivesRightTemplate()
        {
            $config = new Config([
                'layouts' => [
                    'template1' => [
                        'plain' => 'plain-template1',
                        'html' => 'html-template1'
                    ],
                    'template2' => [
                        'plain' => 'plain-template2',
                        'html' => 'html-template2'
                    ],
                ],
                'mails' => [
                    'test1' => [
                        'layout' => 'template1'
                    ],
                    'test2' => [
                        'layout' => 'template2'
                    ]
                ]
            ]);

            $this->assertEquals('plain-template1', $config->getLayoutTemplate('test1', 'plain'));
            $this->assertEquals('html-template1', $config->getLayoutTemplate('test1', 'html'));

            $this->assertEquals('plain-template2', $config->getLayoutTemplate('test2', 'plain'));
            $this->assertEquals('html-template2', $config->getLayoutTemplate('test2', 'html'));
        }

        public function testGetTemplateIsCorrect()
        {
            $config = new Config([
                'mails' => [
                    'test1' => [
                        'template' => [
                            'plain' => 'template1-plain',
                            'html' => 'template1-html'
                        ]
                    ],
                    'test2' => [
                        'template' => [
                            'plain' => 'template2-plain',
                            'html' => 'template2-html'
                        ],
                    ]
                ]
            ]);

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
            $config = new Config([
            ]);
            
            $this->assertNull($config->getTemplate('test3', 'plain'));
        }
        
        public function testSubjectIsReturnedAboveTemplate()
        {
            $config = new Config([
                'mails' => [
                    'test' => [
                        'templates' => [
                            'subject' => 'test-subject'
                        ],
                        'subject' => 'right-subject'
                    ]
                ]
            ]);
            
            $this->assertEquals('right-subject', $config->getSubject('test'));
        }
        
        public function testSubjectTemplateIsCorrect()
        {
            $config = new Config([
                'mails' => [
                    'test' => [
                        'templates' => [
                            'subject' => 'right-subject'
                        ]
                    ]
                ]
            ]);
            
            $this->assertEquals('right-subject', $config->getSubjectTemplate('test'));
        }
       
        public function testNoSubjectTemplateIsReturned()
        {
            $config = new Config([
                'mails' => [
                    'test' => [
                    ]
                ]
            ]);
            
            $this->assertNull($config->getSubjectTemplate('test'));
       }
           
       public function testFromHasCorrectEmailWithNoDomain()
       {
            $config = new Config([
                'domains' => [
                    'default' => 'bla-domain.com'
                ],
                'from' => [
                    'noreply' => [
                        'email' => 'noreply',
                        'name' => 'noreply-name'
                    ]
                ],
                'mails' => [
                    'test' => [
                        'from' => 'noreply'
                    ]
                ]
            ]);
            
            $this->assertEquals('noreply@bla-domain.com', $config->getFrom('test')['email']);
            $this->assertEquals('noreply-name', $config->getFrom('test')['name']);
        }
          
        public function testFromHasCorrectEmailWithDomain()
        {
            $config = new Config([
                'domains' => [
                    'default' => 'bla-domain.com',
                    'domain' => 'my-domain.nl'
                ],
                'from' => [
                    'noreply' => [
                        'email' => 'noreply',
                        'name' => 'noreply-name',
                        'domain' => 'domain'
                    ]
                ],
                'mails' => [
                    'test' => [
                        'from' => 'noreply'
                    ]
                ]
            ]);
            
            $this->assertEquals('noreply@my-domain.nl', $config->getFrom('test')['email']);
            $this->assertEquals('noreply-name', $config->getFrom('test')['name']);
        }
    }
