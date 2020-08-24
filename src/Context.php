<?php

declare(strict_types=1);

namespace Codin\Fault;

use LimitIterator;
use SplFileObject;
use ErrorException;

class Context
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var int
     */
    protected $line;

    public function __construct(string $file, int $line)
    {
        if (!is_file($file)) {
            throw new ErrorException('context file does not exist');
        }
        if ($line < 1) {
            throw new ErrorException('context line cannot be less than 1');
        }
        $this->file = $file;
        $this->line = $line;
    }

    /**
     * Get code context with lines before and after
     */
    public function getPlaceInFile(int $linesBefore = 4, int $linesAfter = 4): array
    {
        $context = [];

        $offset = (int) ($this->line - $linesBefore - 1);

        if ($offset < 0) {
            $linesBefore = 0;
            $offset = 0;
        }

        $file = new SplFileObject($this->file, 'rb');
        $iterator = new LimitIterator($file, $offset, $linesBefore + $linesAfter + 1);
        $index = $offset + 1;

        foreach ($iterator as $text) {
            $context[$index] = $text;
            $index++;
        }

        return $context;
    }
}
