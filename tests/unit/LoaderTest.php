<?php

declare(strict_types=1);

namespace TypistTech\WPContainedHook;

use AspectMock\Test;
use League\Container\Container;

/**
 * @coversDefaultClass \TypistTech\WPContainedHook\Loader
 */
class LoaderTest extends \Codeception\Test\Unit
{
    /**
     * @var Action
     */
    private $actionOne;

    /**
     * @var Action
     */
    private $actionTwo;

    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * @var Filter
     */
    private $filterOne;

    /**
     * @var Filter
     */
    private $filterTwo;

    /**
     * @var Loader
     */
    private $loader;

    /**
     * @covers \TypistTech\WPContainedHook\Loader::add
     */
    public function testAddHookMultipleTimes()
    {
        $this->loader->add($this->actionOne);
        $this->loader->add($this->filterOne);

        $this->assertAttributeSame(
            [ $this->actionOne, $this->filterOne ],
            'hooks',
            $this->loader
        );
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::add
     */
    public function testAddHookUniqueness()
    {
        $this->loader->add($this->filterOne);
        $this->loader->add($this->filterTwo);
        $this->loader->add($this->filterOne);

        $this->assertAttributeSame(
            [ $this->filterOne, $this->filterTwo ],
            'hooks',
            $this->loader
        );
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::add
     */
    public function testAddMultipleHooksAtOnce()
    {
        $this->loader->add($this->actionOne, $this->filterOne);

        $this->assertAttributeSame(
            [ $this->actionOne, $this->filterOne ],
            'hooks',
            $this->loader
        );
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::add
     */
    public function testAddSingleHook()
    {
        $this->loader->add($this->actionOne);

        $this->assertAttributeSame(
            [ $this->actionOne ],
            'hooks',
            $this->loader
        );
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::__construct
     */
    public function testConstructor()
    {
        $this->assertAttributeSame(
            $this->container,
            'container',
            $this->loader
        );
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::run
     */
    public function testRun()
    {
        Test::func(__NAMESPACE__, 'add_action', true);
        Test::func(__NAMESPACE__, 'add_filter', true);

        $actionMock = Test::double($this->actionOne);
        $filterMock = Test::double($this->filterOne);
        $this->loader->add($actionMock->getObject());
        $this->loader->add($filterMock->getObject());

        $this->loader->run();

        $actionMock->verifyInvokedMultipleTimes('setContainer', 1);
        $actionMock->verifyInvokedOnce('setContainer', [ $this->container ]);
        $actionMock->verifyInvokedOnce('registerToContainer');
        $actionMock->verifyInvokedOnce('registerToWordPress');

        $filterMock->verifyInvokedMultipleTimes('setContainer', 1);
        $filterMock->verifyInvokedOnce('setContainer', [ $this->container ]);
        $filterMock->verifyInvokedOnce('registerToContainer');
        $filterMock->verifyInvokedOnce('registerToWordPress');
    }

    protected function _before()
    {
        $this->container = new Container;
        $this->loader    = new Loader($this->container);

        $this->actionOne = new Action('hookOne', 'classOne', 'method', 10, 1);
        $this->actionTwo = new Action('hookTwo', 'classTwo', 'method', 20, 2);
        $this->filterOne = new Filter('hookThree', 'classThree', 'method', 30, 3);
        $this->filterTwo = new Filter('hookFour', 'classFour', 'method', 40, 4);
    }
}
