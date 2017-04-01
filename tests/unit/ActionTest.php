<?php

declare(strict_types=1);

namespace TypistTech\WPContainedHook;

use AspectMock\Test;
use League\Container\Container;
use TypistTech\WPContainedHook\Test\Spy;

/**
 * @coversDefaultClass \TypistTech\WPContainedHook\Action
 */
class ActionTest extends \Codeception\Test\Unit
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
     * For AbstractHookTestTrait use.
     *
     * @param mixed ...$params Parameters of Action constructor.
     *
     * @return Action
     */
    protected function getSubject(...$params): Action
    {
        return new Action(...$params);
    }

    /**
     * @covers \TypistTech\WPContainedHook\Action::getCallbackClosure
     */
    public function testCallbackClosure()
    {
        $action = new Action('spy-alias', 'hook', 'plus', 10, 2);

        $closure = $action->getCallbackClosure($this->container);
        $closure(10, 20);

        $this->containerMock->verifyInvokedMultipleTimes('get', 1);
        $this->containerMock->verifyInvokedOnce('get', [ 'spy-alias' ]);

        $this->assertSame([ 10, 20 ], $this->spy->getInvokedParams());
    }

    protected function _before()
    {
        $this->spy = new Spy;

        $this->containerMock = Test::double(new Container, [
            'get' => $this->spy,
        ]);

        $this->container = $this->containerMock->getObject();
    }
}
