<?php
declare(strict_types=1);

namespace TypistTech\WPContainedHook\Test;

use Mockery;
use Psr\Container\ContainerInterface;
use TypistTech\WPContainedHook\ContainerAwareInterface;

trait ContainerAwareTestTrait
{
    public function testSetAndGet()
    {
        $expected = Mockery::mock(ContainerInterface::class);
        $subject = $this->getSubject();

        $subject->setContainer($expected);
        $actual = $subject->getContainer();

        $this->assertSame($expected, $actual);
    }

    abstract protected function getSubject(): ContainerAwareInterface;
}
