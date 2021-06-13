<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Providers;

use Phalcon\Di\Injectable;
use Phalcon\DiInterface;

abstract class Provider extends Injectable implements ProviderInterface
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
