<?php

namespace App\Providers;

use Phalcon\DiInterface;
use Phalcon\Mvc\User\Component;

/**
 * \App\Providers\AbstractProvider
 *
 * @package App\Providers
 */
abstract class AbstractProvider extends Component implements ProviderInterface
{
    /**
     * The Service name.
     *
     * @var string
     */
    protected $serviceName;

    /**
     * AbstractServiceProvider constructor.
     *
     * @param DiInterface $di The Dependency Injector.
     */
    public function __construct(DiInterface $di)
    {
        $this->setDI($di);
    }

    /**
     * Gets the Service name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->serviceName;
    }
}