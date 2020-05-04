<?php

namespace App\Providers;

use Phalcon\Di\InjectionAwareInterface;

interface ProviderInterface extends InjectionAwareInterface
{

    /**
     * RegisterByPhone application service.
     *
     * @return mixed
     */
    public function register();

    /**
     * Gets the Service name.
     *
     * @return string
     */
    public function getName();

}