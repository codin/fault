<?php

declare(strict_types=1);

namespace Codin\Fault\Handler;

use Codin\Fault\Traits;
use Throwable;

class WebHandler implements HandlerInterface
{
    use Traits\ExceptionMessage, Traits\ExceptionStack;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var string
     */
    protected $resources;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
        $this->resources = __DIR__ . '/../../resources';
    }

    protected function render(Throwable $e): string
    {
        require $this->resources.'/helpers.php';

        \ob_start();

        $stack = $this->getStack($e);

        require $this->resources.'/'.($this->debug ? 'debug' : 'message').'.php';

        return \ob_get_clean() ?: '';
    }

    public function handle(Throwable $e): void
    {
        if (!\headers_sent()) {
            \header('Content-Type: text/html', true, 500);
        }
        echo $this->render($e);
    }
}
