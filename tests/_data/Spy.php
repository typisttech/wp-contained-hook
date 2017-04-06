<?php

declare(strict_types=1);

namespace TypistTech\WPContainedHook\Test;

class Spy
{
    /**
     * @var int[]
     */
    private $invokedParamsForPlus = [];

    /**
     * @var bool
     */
    private $tenCalled = false;

    public function getInvokedParamsForPlus(): array
    {
        return $this->invokedParamsForPlus;
    }

    public function isTenCalled(): bool
    {
        return $this->tenCalled;
    }

    public function plus(int $numberA, int $numberB): int
    {
        $this->invokedParamsForPlus = [ $numberA, $numberB ];

        return $numberA + $numberB;
    }

    public function ten(): int
    {
        $this->tenCalled = true;

        return 10;
    }
}
