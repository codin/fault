<?php

declare(strict_types=1);

namespace Codin\Fault;

use ErrorException;
use Throwable;

class ErrorHandler
{
    protected int $fatalErrors = E_ERROR | E_USER_ERROR | E_COMPILE_ERROR | E_CORE_ERROR | E_PARSE;

    /**
     * @var array<Contracts\ExceptionHandler>
     */
    protected array $listeners = [];

    protected ?string $reservedMemory;

    /**
     * Register callback for handling errors
     */
    public function register(int $reservedMemorySize = 10): void
    {
        if ($reservedMemorySize < 0) {
            $reservedMemorySize = 0;
        }
        $this->reservedMemory = \str_repeat(' ', 1024 * $reservedMemorySize);
        \set_error_handler([$this, 'onError']);
        \set_exception_handler([$this, 'onException']);
        \register_shutdown_function([$this, 'onShutdown']);
    }

    /**
     * Remove callbacks
     */
    public function deregister(): void
    {
        \restore_error_handler();
        \restore_exception_handler();
    }

    /**
     * Add handler
     */
    public function attach(Contracts\ExceptionHandler $listener): void
    {
        $this->listeners[] = $listener;
    }

    /**
     * Handle error
     */
    public function onError(int $level, string $message, string $file, int $line): bool
    {
        if ($level & \error_reporting()) {
            $this->onException(new ErrorException($message, 0, $level, $file, $line));
            if ($level & $this->fatalErrors) {
                $this->terminate();
            }
            return true;
        }
        return false;
    }

    /**
     * Handle exception
     */
    public function onException(Throwable $exception): void
    {
        if (!count($this->listeners)) {
            $this->attach(new Handlers\PrintDump());
        }

        try {
            foreach ($this->listeners as $listener) {
                $listener->handle($exception);
            }
        } catch (Throwable $exceptionalException) {
            \restore_exception_handler();
            throw $exceptionalException;
        }
    }

    /**
     * Handle shutdown callback
     *
     * @return void
     */
    public function onShutdown(): void
    {
        $this->reservedMemory = null;
        $error = \error_get_last();
        if ($error && ($error['type'] & $this->fatalErrors)) {
            $this->onError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    /**
     * Halt execution
     */
    protected function terminate(): void
    {
        exit(1);
    }
}
