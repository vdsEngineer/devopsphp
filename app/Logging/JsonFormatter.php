<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter as MonologJsonFormatter;
use Illuminate\Log\Logger;

class JsonFormatter
{

    public function __invoke(Logger $logger): void
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new MonologJsonFormatter());
        }
    }
}