<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\Injectable;

abstract class Provider extends Injectable implements ProviderInterface
{

    /**
     * Service name
     */
    protected string $serviceName;

    public function __construct(DiInterface $di)
    {
        $this->setDI($di);
    }

    /**
     * Get service name
     */
    public function getName(): string
    {
        return $this->serviceName;
    }

}
