<?php

declare(strict_types=1);

namespace TypistTech\WPContainedHook;

use AspectMock\Test;
use League\Container\Container;
use TypistTech\WPContainedHook\Test\Spy;

/**
 * @coversDefaultClass \TypistTech\WPContainedHook\Filter
 */
class FilterTest extends \Codeception\Test\Unit
{
    use AbstractHookTestTrait;

    /**
     * @var \TypistTech\WPContainedHook\UnitTester
     */
    protected $tester;

    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * @var \AspectMock\Proxy\InstanceProxy
     */
    private $containerMock;

    /**
     * @var Spy
     */
    private $spy;

    /**
     * @covers \TypistTech\WPContainedHook\Filter::getCallbackClosure
     */
    public function testCallbackClosure()
    {
        $action = new Filter('spy-alias', 'hook', 'plus', 10, 2);

        $closure = $action->getCallbackClosure($this->container);
        $actual  = $closure(10, 20);

        $this->containerMock->verifyInvokedMultipleTimes('get', 1);
        $this->containerMock->verifyInvokedOnce('get', [ 'spy-alias' ]);
        $this->assertSame([ 10, 20 ], $this->spy->getInvokedParams());
        $this->assertSame(30, $actual);
    }

    protected function _before()
    {
        $this->spy = new Spy;

        $this->containerMock = Test::double(new Container, [
            'get' => $this->spy,
        ]);

        $this->container = $this->containerMock->getObject();
    }

    /**
     * For AbstractHookTestTrait use.
     *
     * @param mixed ...$params Parameters of Action constructor.
     *
     * @return Filter
     */
    protected function getSubject(...$params): Filter
    {
        return new Filter(...$params);
    }
}
