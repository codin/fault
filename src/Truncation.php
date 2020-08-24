<?php

declare(strict_types=1);

namespace Codin\Fault;

class Truncation
{
    /**
     * @var string
     */
    protected $payload;

    /**
     * @var int
     */
    protected $threshold;

    public function __construct(string $payload, int $threshold = 1024)
    {
        $this->payload = $payload;
        $this->threshold = $threshold;
    }

    public function truncate(): string
    {
        $size = \mb_strlen($this->payload);

        if ($size > $this->threshold) {
            return \mb_substr($this->payload, 0, $this->threshold);
        }

        return $this->payload;
    }
}
