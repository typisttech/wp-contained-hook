<?php

declare(strict_types=1);

namespace TypistTech\WPContainedHook\Test;

class Spy
{
    /**
     * @var int[]
     */
    private $invokedParams = [];

    public function getInvokedParams(): array
    {
        return $this->invokedParams;
    }

    public function plus(int $numberA, int $numberB): int
    {
        $this->invokedParams = [ $numberA, $numberB ];

        return $numberA + $numberB;
    }

}
