<?php

namespace App\Providers;

use Phalcon\Di\InjectionAwareInterface;

/**
 * \App\Providers\ServiceProviderInterface
 *
 * @package App\Providers
 */
interface ProviderInterface extends InjectionAwareInterface
{
    /**
     * Register application service.
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