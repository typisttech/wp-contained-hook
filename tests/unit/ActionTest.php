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
     * @var Action
     */
    private $action;

    /**
     * @var Container
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
     * @covers \TypistTech\WPContainedHook\Action::registerToWordPress
     */
    public function testAddAction()
    {
        $addActionMock = Test::func(__NAMESPACE__, 'add_action', true);

        $this->action->registerToWordPress();

        $addActionMock->verifyInvokedMultipleTimes(1);
        $addActionMock->verifyInvokedOnce([ 'hook', [ $this->action, 'run' ], 10, 2 ]);
    }

    /**
     * @covers \TypistTech\WPContainedHook\Action::run
     */
    public function testRunCallInstanceMethodWithMultipleParams()
    {
        $this->action->run(10, 20);

        $this->assertSame([ 10, 20 ], $this->spy->getInvokedParamsForPlus());
    }

    /**
     * @covers \TypistTech\WPContainedHook\Action::run
     */
    public function testRunCallInstanceMethodWithoutParams()
    {
        $action = new Action('hook', 'spy-alias', 'ten');
        $action->setContainer($this->container);

        $action->run();

        $this->assertTrue($this->spy->isTenCalled());
    }

    /**
     * @covers \TypistTech\WPContainedHook\Action::run
     */
    public function testRunGetFromContainer()
    {
        $this->action->run(10, 20);

        $this->containerMock->verifyInvokedMultipleTimes('get', 1);
        $this->containerMock->verifyInvokedOnce('get', [ 'spy-alias' ]);
    }

    protected function _before()
    {
        $this->spy = new Spy;

        $this->containerMock = Test::double(new Container, [
            'get' => $this->spy,
        ]);
        $this->container = $this->containerMock->getObject();

        $this->action = new Action('hook', 'spy-alias', 'plus', 10, 2);
        $this->action->setContainer($this->container);
    }

    /**
     * For AbstractHookTestTrait use.
     *
     * @return string
     */
    protected function getIdPrefix(): string
    {
        return 'action';
    }

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
}
