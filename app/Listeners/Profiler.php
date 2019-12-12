<?php

namespace App\Listeners;

use Phalcon\Db\Profiler as DbProfiler;
use Phalcon\Events\Event;

class Profiler extends Listener
{

    protected $logger;

    protected $profiler;

    public function __construct()
    {
        $this->logger = $this->getLogger('sql');

        $this->profiler = new DbProfiler();
    }

    public function beforeQuery(Event $event, $connection)
    {
        $this->profiler->startProfile($connection->getSQLStatement(), $connection->getSQLVariables());
    }

    public function afterQuery(Event $event, $connection)
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