<?php

declare(strict_types=1);

namespace TypistTech\WPContainedHook;

use League\Container\Container;

trait AbstractHookTestTrait
{
    /**
     * @var AbstractHook
     */
    private $subject;

    abstract protected function getIdPrefix(): string;

    abstract protected function getSubject(...$params): AbstractHook;

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook::__construct
     */
    public function testConstructor()
    {
        $subject = $this->getSubject('hookOne', 'classOne', 'methodOne', 100, 11);

        $this->assertAttributeSame('hookOne', 'hook', $subject);
        $this->assertAttributeSame('classOne', 'classIdentifier', $subject);
        $this->assertAttributeSame('methodOne', 'callbackMethod', $subject);
        $this->assertAttributeSame(100, 'priority', $subject);
        $this->assertAttributeSame(11, 'acceptedArgs', $subject);
    }

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook::__construct
     */
    public function testDefaultValues()
    {
        $subject = $this->getSubject('hookOne', 'classOne', 'methodOne');

        $this->assertAttributeSame(10, 'priority', $subject);
        $this->assertAttributeSame(1, 'acceptedArgs', $subject);
    }

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook::getContainer
     */
    public function testGetContainer()
    {
        $subject = $this->getSubject('hookOne', 'classOne', 'methodOne', 100, 11);
        $subject->setContainer($this->container);

        $actual = $subject->getContainer();

        $this->assertSame($this->container, $actual);
    }

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook::getId
     */
    public function testGetId()
    {
        $subject = $this->getSubject('hookOne', 'classOne', 'methodOne', 100, 11);

        $actual = $subject->getId();

        $expected = $this->getIdPrefix() . '-hookOne-classOne-methodOne-100-11';

        $this->assertSame($expected, $actual);
    }

    /**
     * @coversNothing
     */
    public function testIsInstanceOfAbstractHook()
    {
        $subject = $this->getSubject('hookOne', 'classOne', 'methodOne');

        $this->assertInstanceOf(AbstractHook::class, $subject);
    }

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook::registerToContainer
     */
    public function testRegisterToContainer()
    {
        $container = new Container;
        $subject   = $this->getSubject('hookOne', 'classOne', 'methodOne', 100, 11);
        $subject->setContainer($container);

        $subject->registerToContainer();
        $actual = $container->get($subject->getId());

        $this->assertSame($subject, $actual);
    }

    /**
     * @covers \TypistTech\WPContainedHook\AbstractHook::setContainer
     */
    public function testSetContainer()
    {
        $container = new Container;
        $subject   = $this->getSubject('hookOne', 'classOne', 'methodOne', 100, 11);

        $subject->setContainer($container);

        $this->assertAttributeSame(
            $container,
            'container',
            $subject
        );
    }
}
