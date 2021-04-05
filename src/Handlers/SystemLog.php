<?php

declare(strict_types=1);

namespace Codin\Fault\Handlers;

use Codin\Fault\Contracts\ExceptionHandler;
use Codin\Fault\Traits;
use Throwable;

class SystemLog implements ExceptionHandler
{
    use Traits\ExceptionMessage;

    protected string $ident;

    public function __construct(string $ident = 'app')
    {
        $this->ident = $ident;
    }

    public function handle(Throwable $e): void
    {
        \openlog($this->ident, LOG_PID | LOG_PERROR, LOG_USER);
        \syslog(LOG_ERR, $this->getMessageWithSource($e));
        \closelog();
    }
}
