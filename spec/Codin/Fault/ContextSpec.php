<?php

namespace spec\Codin\Fault;

use PhpSpec\ObjectBehavior;

class ContextSpec extends ObjectBehavior
{
    public function it_should_get_place_in_file()
    {
        $path = sys_get_temp_dir();
        $name = sprintf('phpspec-test-%u', random_int(1, PHP_INT_MAX));
        $filepath = sprintf('%s/%s.php', $path, $name);

        file_put_contents($filepath, implode("\n", range(1, 100))."\n");

        $this->beConstructedWith($filepath, 50);

        $this->getPlaceInFile(4, 4)->shouldReturn([
            46 => "46\n",
            47 => "47\n",
            48 => "48\n",
            49 => "49\n",
            50 => "50\n",
            51 => "51\n",
            52 => "52\n",
            53 => "53\n",
            54 => "54\n",
        ]);

        $this->getPlaceInFile(0, 2)->shouldReturn([
            50 => "50\n",
            51 => "51\n",
            52 => "52\n",
        ]);

        $this->getPlaceInFile(2, 0)->shouldReturn([
            48 => "48\n",
            49 => "49\n",
            50 => "50\n",
        ]);

        unlink($filepath);
    }
}
