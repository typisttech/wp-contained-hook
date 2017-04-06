<?php

declare(strict_types=1);

namespace TypistTech\WPContainedHook;

trait AbstractHookTestTrait
{
    abstract protected function getIdPrefix(): string;

    abstract protected function getSubject(...$params): AbstractHook;

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook::__construct
     */
    public function testDefaultValues()
    {
        $subject = $this->getSubject('classOne', 'hookOne', 'methodOne');

        $actualPriority = $subject->getPriority();
        $actualAcceptedArgs = $subject->getAcceptedArgs();

        $this->assertSame(10, $actualPriority);
        $this->assertSame(1, $actualAcceptedArgs);
    }

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook::getAcceptedArgs
     */
    public function testGetAcceptedArgs()
    {
        $subject = $this->getSubject('classOne', 'hookOne', 'methodOne', 100, 11);

        $actual = $subject->getAcceptedArgs();

        $this->assertSame(11, $actual);
    }

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook::getHook
     */
    public function testGetHook()
    {
        $subject = $this->getSubject('classOne', 'hookOne', 'methodOne');

        $actual = $subject->getHook();

        $this->assertSame('hookOne', $actual);
    }

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook::getId
     */
    public function testGetId()
    {
        $subject = $this->getSubject('classOne', 'hookOne', 'methodOne', 100, 11);

        $actual = $subject->getId();

        $expected = $this->getIdPrefix() . '-classOne-hookOne-methodOne-100-11';

        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook::getPriority
     */
    public function testGetPriority()
    {
        $subject = $this->getSubject('classOne', 'hookOne', 'methodOne', 100, 11);

        $actual = $subject->getPriority();

        $this->assertSame(100, $actual);
    }

    /**
     * @coversNothing
     */
    public function testIsInstanceOfAbstractHook()
    {
        $subject = $this->getSubject('classOne', 'hookOne', 'methodOne');

        $this->assertInstanceOf(AbstractHook::class, $subject);
    }
}
