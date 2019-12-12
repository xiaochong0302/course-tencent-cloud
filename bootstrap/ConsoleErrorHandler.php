<?php

namespace Bootstrap;

use App\Library\Logger as AppLogger;
use Phalcon\Mvc\User\Component as UserComponent;

class ConsoleErrorHandler extends UserComponent
{

    protected $logger;

    public function __construct()
    {
        $this->logger = $this->getLogger();

        set_error_handler([$this, 'handleError']);

        set_exception_handler([$this, 'handleException']);
    }

    public function handleError($no, $str, $file, $line)
    {
        $content = compact('no', 'str', 'file', 'line');

        $this->logger->error('Console Error ' . kg_json_encode($content));
    }

    public function handleException($e)
    {
        $content = [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'message' => $e->getMessage(),
        ];

        $this->logger->error('Console Exception ' . kg_json_encode($content));
    }

    protected function getLogger()
    {
        $logger = new AppLogger();

        return $logger->getInstance('console');
    }

}
