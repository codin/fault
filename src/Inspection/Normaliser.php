<?php

declare(strict_types=1);

namespace Codin\Fault\Inspection;

use Codin\Fault\Exceptions;

class Normaliser
{
    /**
     * @param mixed $value
     */
    public function normalise($value, int $truncate = 1024): string
    {
        if ($value === null) {
            return 'null';
        } elseif ($value === false) {
            return 'false';
        } elseif ($value === true) {
            return 'true';
        } elseif (\is_integer($value) || \is_float($value)) {
            return (string) $value;
        } elseif (\is_object($value)) {
            return 'Object '.\get_class($value);
        } elseif (\is_resource($value)) {
            return 'Resource '.\get_resource_type($value);
        } elseif (\is_array($value)) {
            return 'Array '.\count($value);
        } elseif (\is_string($value)) {
            return $this->truncate($value, $truncate);
        }

        throw Exceptions\FrameError::normaliseUnknown($value);
    }

    public function truncate(string $value, int $threshold): string
    {
        if (\mb_strlen($value) > $threshold) {
            return \mb_substr($value, 0, $threshold).' (truncated)';
        }

        return $value;
    }
}
