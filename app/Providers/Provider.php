<?php

namespace App\Providers;

use Phalcon\DiInterface;
use Phalcon\Mvc\User\Component;

abstract class Provider extends Component implements ProviderInterface
{

    /**
     * Service name
     *
     * @var string
     */
    protected $serviceName;

    /**
     * Service provider constructor
     *
     * @param DiInterface $di
     */
    public function __construct(DiInterface $di)
    {
        $this->setDI($di);
    }

    /**
     * Get service name
     *
     * @return string
     */
    public function getName()
    {
        return $this->serviceName;
    }

}