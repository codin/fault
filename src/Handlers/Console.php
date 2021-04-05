<?php

declare(strict_types=1);

namespace Codin\Fault\Handlers;

use Codin\Fault\Contracts\ExceptionHandler;
use Codin\Fault\Traits;
use ErrorException;
use Throwable;

class Console implements ExceptionHandler
{
    use Traits\ExceptionMessage, Traits\ExceptionStack;

    protected array $styles = [
        'bold' => '1',
        'dark' => '2',
        'italic' => '3',
        'underline' => '4',
        'blink' => '5',
        'reverse' => '7',
        'concealed' => '8',

        'default' => '39',
        'black' => '30',
        'red' => '31',
        'green' => '32',
        'yellow' => '33',
        'blue' => '34',
        'magenta' => '35',
        'cyan' => '36',
        'light_gray' => '37',

        'dark_gray' => '90',
        'light_red' => '91',
        'light_green' => '92',
        'light_yellow' => '93',
        'light_blue' => '94',
        'light_magenta' => '95',
        'light_cyan' => '96',
        'white' => '97',

        'bg_default' => '49',
        'bg_black' => '40',
        'bg_red' => '41',
        'bg_green' => '42',
        'bg_yellow' => '43',
        'bg_blue' => '44',
        'bg_magenta' => '45',
        'bg_cyan' => '46',
        'bg_light_gray' => '47',

        'bg_dark_gray' => '100',
        'bg_light_red' => '101',
        'bg_light_green' => '102',
        'bg_light_yellow' => '103',
        'bg_light_blue' => '104',
        'bg_light_magenta' => '105',
        'bg_light_cyan' => '106',
        'bg_white' => '107',
    ];

    /**
     * @var resource
     */
    protected $stream;

    protected bool $supportsANSI;

    public function __construct(?string $io = null)
    {
        if (null === $io) {
            $io = 'php://stderr';
        }
        $stream = \fopen($io, 'w');
        if (false === $stream) {
            throw new ErrorException('failed to open stream: '.$io);
        }
        $this->stream = $stream;
        $this->supportsANSI = \stream_isatty($this->stream);
    }

    public function __destruct()
    {
        \fclose($this->stream);
    }

    protected function write(string $msg, array $styles = []): void
    {
        if ($this->supportsANSI && count($styles)) {
            $sequences = \array_intersect_key($this->styles, \array_flip($styles));
            $msg = $this->esc(\implode(';', $sequences))  . $msg . $this->esc('0');
        }
        \fwrite($this->stream, $msg);
    }

    protected function writeln(string $msg, array $styles = []): void
    {
        $this->write($msg."\n", $styles);
    }

    protected function esc(string $value): string
    {
        return "\033[{$value}m";
    }

    public function handle(Throwable $e): void
    {
        $indent = '    ';
        $doubleIndent = $indent.$indent;

        $this->writeln('');

        foreach ($this->getStack($e) as $trace) {
            $text = $this->getMessage($trace->getException());
            $this->writeln($text."\n", ['bold']);
            $this->writeln('');
            $this->writeln($indent.$trace->getException()->getFile().':'.$trace->getException()->getLine());
            $this->writeln('');

            foreach ($trace->getContext()->getPlaceInFile() as $num => $line) {
                $styles = ['dark'];
                $lineIndent = $doubleIndent;

                if ($num === $trace->getException()->getLine()) {
                    $styles = ['bold', 'red'];
                    $lineIndent = $indent.'--> ';
                }

                $text = $lineIndent.$num.' '.\rtrim($line);
                $this->writeln($text, $styles);
            }

            $this->writeln('');

            foreach ($trace->getFrames() as $index => $frame) {
                $this->writeln($indent.$frame->getFile().':'.$frame->getLine());
                foreach ($frame->getArguments() as $key => $value) {
                    $this->writeln($doubleIndent.$key.': '.rtrim($value));
                }
            }

            $this->writeln('');
        }

        $this->writeln('');
    }
}
