<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use Phalcon\Db\Adapter as DbAdapter;
use Phalcon\Db\Profiler as DbProfiler;
use Phalcon\Events\Event as PhEvent;
use Phalcon\Logger\Adapter\File as FileLogger;

class Db extends Listener
{

    /**
     * @var FileLogger
     */
    protected $logger;

    /**
     * @var DbProfiler
     */
    protected $profiler;

    public function __construct()
    {
        $this->logger = $this->getLogger('sql');

        $this->profiler = new DbProfiler();
    }

    /**
     * @param PhEvent $event
     * @param DbAdapter $connection
     */
    public function beforeQuery(PhEvent $event, $connection)
    {
        $this->profiler->startProfile($connection->getSqlStatement(), $connection->getSqlVariables());
    }

    /**
     * @param PhEvent $event
     * @param DbAdapter $connection
     */
    public function afterQuery(PhEvent $event, $connection)
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