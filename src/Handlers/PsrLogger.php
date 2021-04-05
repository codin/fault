<?php

declare(strict_types=1);

namespace Codin\Fault\Handlers;

use Codin\Fault\Contracts\ExceptionHandler;
use Codin\Fault\Traits;
use Psr\Log\LoggerInterface;
use Throwable;

class PsrLogger implements ExceptionHandler
{
    use Traits\ExceptionMessage;

    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $e): void
    {
        $severity = E_USER_ERROR;

        if ($e instanceof \ErrorException) {
            $severity = $e->getSeverity();
        }

        switch ($severity) {
            case E_ERROR:
            case E_RECOVERABLE_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_PARSE:
                $this->logger->error($this->getMessage($e), ['exception' => $e]);
                break;
            case E_WARNING:
            case E_USER_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
                $this->logger->warning($this->getMessage($e), ['exception' => $e]);
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                $this->logger->notice($this->getMessage($e), ['exception' => $e]);
                break;
            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $this->logger->info($this->getMessage($e), ['exception' => $e]);
                break;
        }
    }
}
