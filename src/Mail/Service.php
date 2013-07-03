<?php
    namespace Mail;

    use Zend\Mail as ZendMail;
    use Zend\ServiceManager\ServiceLocatorAwareInterface;
    use Zend\ServiceManager\ServiceLocatorAwareTrait;
    use Zend\View\Model\ViewModel;
    use Zend\View\Renderer\RendererInterface;
    use Zend\Mime;

    /**
     * Class Service
     * @package Mail
     */
    class Service implements ServiceLocatorAwareInterface
    {
        use ServiceLocatorAwareTrait;

        /**
         * @var RendererInterface
         */
        protected $renderer;

        /**
         * @var Config
         */
        protected $config;

        /**
         * @var ZendMail\Transport\TransportInterface
         */
        protected $transport;

        /**
         * @param $config
         */
        public function __construct(Config $config)
        {
            $this->setConfig($config);
        }

        /**
         * @return \Mail\Config
         */
        public function getConfig()
        {
            return $this->config;
        }

        /**
         * @param \Mail\Config $config
         * @return $this
         */
        public function setConfig(Config $config)
        {
            $this->config = $config;
            return $this;
        }

        /**
         * @param ZendMail\Message $message
         * @param string $message
         * @param array $variables
         * @return ZendMail\Message
         */
        public function sendMail(ZendMail\Message $message, $emailAlias, array $variables = array())
        {
            $renderer = $this->getRenderer();

            $plain = '';
            if (null !== $template = $this->getConfig()->getTemplate($emailAlias, Config::TYPE_PLAIN)) {
                $viewModel = new ViewModel($variables);
                $viewModel->setTemplate($template);
                $plain = $renderer->render($viewModel);
            }

            if (null !== $template = $this->getConfig()->getLayoutTemplate($emailAlias, Config::TYPE_PLAIN)) {
                $viewModel = new ViewModel(array_merge($variables, array(
                    'content' => $plain
                )));
                $viewModel->setTemplate($template);
                $plain = $renderer->render($viewModel);
            }

            $html = '';
            if (null !== $template = $this->getConfig()->getTemplate($emailAlias, Config::TYPE_HTML)) {
                $viewModel = new ViewModel($variables);
                $viewModel->setTemplate($template);
                $html = $renderer->render($viewModel);
            }

            if (null !== $template = $this->getConfig()->getLayoutTemplate($emailAlias, Config::TYPE_HTML)) {
                $viewModel = new ViewModel(array_merge($variables, array(
                    'content' => $html
                )));
                $viewModel->setTemplate($template);
                $html = $renderer->render($viewModel);
            }

            $body = new Mime\Message();
            if (!empty($html) && !empty($plain)) {
                $htmlPart = new Mime\Part($html);
                $htmlPart->type = 'text/html';

                $plainPart = new Mime\Part($plain);
                $plainPart->type = 'text/plain';

                $body->setParts(array($plainPart, $htmlPart));
            } elseif (!empty($html)) {
                $htmlPart = new Mime\Part($html);
                $htmlPart->type = 'text/html';
                $body->setParts(array($htmlPart));
            } else {
                $plainPart = new Mime\Part($html);
                $plainPart->type = 'text/plain';
                $body->setParts(array($plainPart));
            }

            $from = $this->getConfig()->getFrom($emailAlias);
            $message->setSubject($this->getSubject($emailAlias, $variables))
                ->setEncoding('UTF-8')
                ->setBody($body)
                ->setFrom($from['email'], $from['name']);

            if (count($body->getParts()) == 2) {
                $message->getHeaders()->get('content-type')->setType('multipart/alternative');
            }

            if (null === $transport = $this->getTransport()) {
                throw new Exception\RuntimeException('No transport could be alocated');
            }
            $transport->send($message);
            return $message;
        }

        /**
         * @param string $emailAlias
         * @param array $variables
         * @return null|string
         */
        public function getSubject($emailAlias, array $variables = [])
        {
            if (null !== $subject = $this->getConfig()->getSubject($emailAlias)) {
                return $subject;
            }

            if (null !== $template = $this->getConfig()->getSubjectTemplate($emailAlias)) {
                $renderer = $this->getRenderer();
                $viewModel = new ViewModel($variables);
                $viewModel->setTemplate($template);
                return $renderer->render($viewModel);
            }
            return null;
        }

        /**
         * @return RendererInterface
         */
        public function getRenderer()
        {
            if (!$this->renderer) {
                $this->renderer = $this->getServiceLocator()->get('viewrenderer');
            }
            return $this->renderer;
        }

        /**
         * @param RendererInterface $renderer
         * @return $this
         */
        public function setRenderer(RendererInterface $renderer)
        {
            $this->renderer = $renderer;
            return $this;
        }

        /**
         * @param ZendMail\Transport\TransportInterface $transport
         * @return $this
         */
        public function setTransport(ZendMail\Transport\TransportInterface $transport)
        {
            $this->transport = $transport;
            return $this;
        }

        /**
         * @return ZendMail\Transport\TransportInterface
         */
        public function getTransport()
        {
            if (!$this->transport) {
                $this->transport = $this->getConfig()->getTransport();
            }
            return $this->transport;
        }
    }
