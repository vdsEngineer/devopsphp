<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\JsonFormatter as MonologJsonFormatter;

class JsonFormatter
{
    public function __invoke(Logger $logger): void
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new MonologJsonFormatter);
        }
    }
}
