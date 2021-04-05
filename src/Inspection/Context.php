<?php

declare(strict_types=1);

namespace Codin\Fault\Inspection;

use Codin\Fault\Exceptions;
use LimitIterator;
use SplFileObject;

class Context
{
    protected string $file;

    protected int $line;

    public function __construct(string $file, int $line)
    {
        if (!is_file($file)) {
            throw Exceptions\ContextError::fileNotFound($file);
        }

        if ($line < 1) {
            throw Exceptions\ContextError::invalidLineNumber();
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
