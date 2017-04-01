<?php

declare(strict_types=1);

namespace TypistTech\WPContainedHook;

trait AbstractHookTestTrait
{
    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook
     */
    public function testGetAcceptedArgs()
    {
        $subject = $this->getSubject('classOne', 'hookOne', 'methodOne', 100, 11);

        $actual = $subject->getAcceptedArgs();

        $this->assertSame(11, $actual);
    }

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook
     */
    public function testDefaultAcceptedArgs()
    {
        $subject = $this->getSubject('classOne', 'hookOne', 'methodOne');

        $actual = $subject->getAcceptedArgs();

        $this->assertSame(1, $actual);
    }

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook
     */
    public function testGetPriority()
    {
        $subject = $this->getSubject('classOne', 'hookOne', 'methodOne', 100, 11);

        $actual = $subject->getPriority();

        $this->assertSame(100, $actual);
    }

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook
     */
    public function testDefaultPriority()
    {
        $subject = $this->getSubject('classOne', 'hookOne', 'methodOne');

        $actual = $subject->getPriority();

        $this->assertSame(10, $actual);
    }

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook
     */
    public function testGetHook()
    {
        $subject = $this->getSubject('classOne', 'hookOne', 'methodOne');

        $actual = $subject->getHook();

        $this->assertSame('hookOne', $actual);
    }

    /**
     * @coversNothing
     */
    public function testIsInstanceOfAbstractHook()
    {
        $subject = $this->getSubject('classOne', 'hookOne', 'methodOne');

        $this->assertInstanceOf(AbstractHook::class, $subject);
    }

    abstract protected function getSubject(...$params): AbstractHook;
}
