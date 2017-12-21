<?php

namespace Sfynx\BrowserBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Sfynx\BrowserBundle\Manager\Lib\BrowscapHandler;
use Symfony\Component\HttpFoundation\Cookie;

class HandlerCache
{
    protected $param;

    /**
     * Constructor.
     *
     * @param Browscap $client
     */
    public function __construct(BrowscapHandler $client) {
        $this->client = $client;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($event->getRequest()->cookies->has('sfynx-browser')) {
            return;
        }
        $dateExpire = $this->getDateExpire();
        $response = $event->getResponse();

        $response->headers->setCookie(new Cookie('sfynx-browser', serialize($this->client->getBrowser()), $dateExpire));
        $event->setResponse($response);
    }

    /**
     * {@inheritdoc}
     */
    public function getDateExpire()
    {
        $dateExpire = 0;
        if ($this->param->date_expire && !empty($this->param->date_interval)) {
            $dateExpire = new \DateTime("NOW");
            if (is_numeric($this->param->date_interval)) {
                $dateExpire = time() + intVal($this->param->date_interval);
            } else {
                $dateExpire->add(new \DateInterval($this->param->date_interval));
            }
        }

        return $dateExpire;
    }

    /**
     * {@inheritdoc}
     */
    public function setParams(array $option)
    {
        $this->param = (object) $option;
    }
}
