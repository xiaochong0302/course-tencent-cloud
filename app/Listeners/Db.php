<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Listeners;

use Phalcon\Db\Adapter\AdapterInterface as DbAdapterInterface;
use Phalcon\Db\Profiler as DbProfiler;
use Phalcon\Events\Event as PhEvent;
use Phalcon\Logger\Logger;

class Db extends Listener
{

    /**
     * @var Logger
     */
    protected Logger $logger;

    /**
     * @var DbProfiler
     */
    protected DbProfiler $profiler;

    public function __construct()
    {
        $this->logger = $this->getLogger('sql');

        $this->profiler = new DbProfiler();
    }

    public function beforeQuery(PhEvent $event, DbAdapterInterface $connection): void
    {
        $this->profiler->startProfile($connection->getSqlStatement(), $connection->getSqlVariables());
    }

    public function afterQuery(PhEvent $event, DbAdapterInterface $connection): void
    {
        $this->profiler->stopProfile();

        foreach ($this->profiler->getProfiles() as $profile) {

            $statement = sprintf('sql statement: %s', $profile->getSqlStatement());
            $elapsedTime = sprintf('elapsed time: %03f seconds', $profile->getTotalElapsedSeconds());

            $this->logger->debug('--- BEGIN OF QUERY ---');
            $this->logger->debug($statement);

            if ($profile->getSqlVariables()) {
                $variables = sprintf('sql variables: %s', kg_json_encode($profile->getSqlVariables()));
                $this->logger->debug($variables);
            }

            $this->logger->debug($elapsedTime);
            $this->logger->debug('--- END OF QUERY ---');
        }
    }

}
