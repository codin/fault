<?php

declare(strict_types=1);

namespace Codin\Fault\Handler;

use Codin\Fault\Traits;
use Throwable;

class SyslogHandler implements HandlerInterface
{
    use Traits\ExceptionMessage;

    /**
     * @var string
     */
    protected $ident;

    /**
     * @var callable
     */
    protected $handler;

    public function __construct(string $ident = 'app', callable $handler = null)
    {
        $this->ident = $ident;
        $this->handler = $handler ?: function (Throwable $e): void {
            \openlog($this->ident, LOG_PID | LOG_PERROR, LOG_USER);
            \syslog(LOG_ERR, $this->getMessageWithSource($e));
            \closelog();
        };
    }

    public function handle(Throwable $e): void
    {
        ($this->handler)($e);
    }
}
