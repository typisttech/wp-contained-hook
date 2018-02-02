<?php

declare(strict_types=1);

namespace TypistTech\WPContainedHook;

use AspectMock\Test;
use Codeception\Test\Unit;
use League\Container\Container;
use TypistTech\WPContainedHook\Test\Spy;

/**
 * @coversDefaultClass \TypistTech\WPContainedHook\Filter
 */
class FilterTest extends Unit
{
    use AbstractHookTestTrait;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var \AspectMock\Proxy\InstanceProxy
     */
    private $containerMock;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var Spy
     */
    private $spy;

    /**
     * @covers \TypistTech\WPContainedHook\Filter::registerToWordPress
     */
    public function testAddFilter()
    {
        $addFilterMock = Test::func(__NAMESPACE__, 'add_filter', true);

        $this->filter->registerToWordPress();

        $addFilterMock->verifyInvokedMultipleTimes(1);
        $addFilterMock->verifyInvokedOnce([ 'hook', [ $this->filter, 'run' ], 10, 2 ]);
    }

    /**
     * @covers \TypistTech\WPContainedHook\Filter::run
     */
    public function testRunCallInstanceMethodWithMultipleParams()
    {
        $this->filter->run(10, 20);

        $this->assertSame([ 10, 20 ], $this->spy->getInvokedParamsForPlus());
    }

    /**
     * @covers \TypistTech\WPContainedHook\Filter::run
     */
    public function testRunCallInstanceMethodWithoutParams()
    {
        $filter = new Filter('hook', 'spy-alias', 'ten');
        $filter->setContainer($this->container);

        $actual = $filter->run();

        $this->assertTrue($this->spy->isTenCalled());
        $this->assertSame(10, $actual);
    }

    /**
     * @covers \TypistTech\WPContainedHook\Filter::run
     */
    public function testRunGetFromContainer()
    {
        $this->filter->run(10, 20);

        $this->containerMock->verifyInvokedMultipleTimes('get', 1);
        $this->containerMock->verifyInvokedOnce('get', [ 'spy-alias' ]);
    }

    /**
     * @covers \TypistTech\WPContainedHook\Filter::run
     */
    public function testRunReturnResult()
    {
        $actual = $this->filter->run(10, 20);

        $this->assertSame(30, $actual);
    }

    protected function _before()
    {
        $this->spy = new Spy();

        $this->containerMock = Test::double(new Container(), [
            'get' => $this->spy,
        ]);

        $this->container = $this->containerMock->getObject();

        $this->filter = new Filter('hook', 'spy-alias', 'plus', 10, 2);
        $this->filter->setContainer($this->container);
    }

    /**
     * For AbstractHookTestTrait use.
     *
     * @return string
     */
    protected function getIdPrefix(): string
    {
        return 'filter';
    }

    /**
     * For AbstractHookTestTrait use.
     *
     * @param mixed ...$params Parameters of Filter constructor.
     *
     * @return Filter
     */
    protected function getSubject(...$params): Filter
    {
        return new Filter(...$params);
    }
}
