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
     * @var \AspectMock\Proxy\FuncProxy
     */
    private $addActionMock;

    /**
     * @var \AspectMock\Proxy\FuncProxy
     */
    private $addFilterMock;

    /**
     * @var \Closure
     */
    private $closureFour;

    /**
     * @var \Closure
     */
    private $closureOne;

    /**
     * @var \Closure
     */
    private $closureThree;

    /**
     * @var \Closure
     */
    private $closureTwo;

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
     * @covers \TypistTech\WPContainedHook\Loader::addAction
     */
    public function testAddActionMultipleTimes()
    {
        $this->loader->addAction($this->actionOne);
        $this->loader->addAction($this->actionTwo);

        $this->assertAttributeSame(
            [ $this->actionOne, $this->actionTwo ],
            'actions',
            $this->loader
        );
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::addAction
     */
    public function testAddActionUniqueness()
    {
        $this->loader->addAction($this->actionOne);
        $this->loader->addAction($this->actionTwo);
        $this->loader->addAction($this->actionOne);

        $this->assertAttributeSame(
            [ $this->actionOne, $this->actionTwo ],
            'actions',
            $this->loader
        );
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::addFilter
     */
    public function testAddFilterMultipleTimes()
    {
        $this->loader->addFilter($this->filterOne);
        $this->loader->addFilter($this->filterTwo);

        $this->assertAttributeSame(
            [ $this->filterOne, $this->filterTwo ],
            'filters',
            $this->loader
        );
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::addFilter
     */
    public function testAddFilterUniqueness()
    {
        $this->loader->addFilter($this->filterOne);
        $this->loader->addFilter($this->filterTwo);
        $this->loader->addFilter($this->filterOne);

        $this->assertAttributeSame(
            [ $this->filterOne, $this->filterTwo ],
            'filters',
            $this->loader
        );
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::addAction
     */
    public function testAddMultipleActionsAtOnce()
    {
        $this->loader->addAction($this->actionOne, $this->actionTwo);

        $this->assertAttributeSame(
            [ $this->actionOne, $this->actionTwo ],
            'actions',
            $this->loader
        );
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::addFilter
     */
    public function testAddMultipleFiltersAtOnce()
    {
        $this->loader->addFilter($this->filterOne, $this->filterTwo);

        $this->assertAttributeSame(
            [ $this->filterOne, $this->filterTwo ],
            'filters',
            $this->loader
        );
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::addAction
     */
    public function testAddSingleAction()
    {
        $this->loader->addAction($this->actionOne);

        $this->assertAttributeSame(
            [ $this->actionOne ],
            'actions',
            $this->loader
        );
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::addFilter
     */
    public function testAddSingleFilter()
    {
        $this->loader->addFilter($this->filterOne);

        $this->assertAttributeSame(
            [ $this->filterOne ],
            'filters',
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
    public function testRunBothActionsAndFilters()
    {
        $this->loader->addAction($this->actionOne);
        $this->loader->addAction($this->actionTwo);
        $this->loader->addFilter($this->filterOne);
        $this->loader->addFilter($this->filterTwo);

        $this->loader->run();

        $this->addActionMock->verifyInvokedMultipleTimes(2);
        $this->addActionMock->verifyInvokedOnce([ 'hookOne', $this->closureOne, 10, 1 ]);
        $this->addActionMock->verifyInvokedOnce([ 'hookTwo', $this->closureTwo, 20, 2 ]);
        $this->addFilterMock->verifyInvokedMultipleTimes(2);
        $this->addFilterMock->verifyInvokedOnce([ 'hookThree', $this->closureThree, 30, 3 ]);
        $this->addFilterMock->verifyInvokedOnce([ 'hookFour', $this->closureFour, 40, 4 ]);
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::run
     */
    public function testRunMultipleActions()
    {
        $this->loader->addAction($this->actionOne);
        $this->loader->addAction($this->actionTwo);

        $this->loader->run();

        $this->addActionMock->verifyInvokedMultipleTimes(2);
        $this->addActionMock->verifyInvokedOnce([ 'hookOne', $this->closureOne, 10, 1 ]);
        $this->addActionMock->verifyInvokedOnce([ 'hookTwo', $this->closureTwo, 20, 2 ]);
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::run
     */
    public function testRunMultipleFilters()
    {
        $this->loader->addFilter($this->filterOne);
        $this->loader->addFilter($this->filterTwo);

        $this->loader->run();

        $this->addFilterMock->verifyInvokedMultipleTimes(2);
        $this->addFilterMock->verifyInvokedOnce([ 'hookThree', $this->closureThree, 30, 3 ]);
        $this->addFilterMock->verifyInvokedOnce([ 'hookFour', $this->closureFour, 40, 4 ]);
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::run
     */
    public function testRunSingleAction()
    {
        $this->loader->addAction($this->actionOne);

        $this->loader->run();

        $this->addActionMock->verifyInvokedMultipleTimes(1);
        $this->addActionMock->verifyInvokedOnce([ 'hookOne', $this->closureOne, 10, 1 ]);
    }

    /**
     * @covers \TypistTech\WPContainedHook\Loader::run
     */
    public function testRunSingleFilter()
    {
        $this->loader->addFilter($this->filterOne);

        $this->loader->run();

        $this->addFilterMock->verifyInvokedMultipleTimes(1);
        $this->addFilterMock->verifyInvokedOnce([ 'hookThree', $this->closureThree, 30, 3 ]);
    }

    protected function _before()
    {
        $this->container = new Container;
        $this->loader    = new Loader($this->container);

        $this->setUpClosure();
        $this->setUpActionAndFilterMocks();
        $this->setUpWordPressFunctionMocks();
    }

    private function setUpClosure()
    {
        $this->closureOne = function () {
            return 'i am a closure one';
        };

        $this->closureTwo = function () {
            return 'i am a closure two';
        };

        $this->closureThree = function () {
            return 'i am a closure three';
        };

        $this->closureFour = function () {
            return 'i am a closure four';
        };
    }

    protected function setUpActionAndFilterMocks()
    {
        $closureOne      = $this->closureOne;
        $this->actionOne = Test::double(
            new Action('classOne', 'hookOne', 'method', 10, 1),
            [
                'getCallbackClosure' => function () use ($closureOne) {
                    return $closureOne;
                },
            ]
        )->getObject();

        $closureTwo      = $this->closureTwo;
        $this->actionTwo = Test::double(
            new Action('classTwo', 'hookTwo', 'method', 20, 2),
            [
                'getCallbackClosure' => function () use ($closureTwo) {
                    return $closureTwo;
                },
            ]
        )->getObject();


        $closureThree    = $this->closureThree;
        $this->filterOne = Test::double(
            new Filter('classThree', 'hookThree', 'method', 30, 3),
            [
                'getCallbackClosure' => function () use ($closureThree) {
                    return $closureThree;
                },
            ]
        )->getObject();

        $closureFour     = $this->closureFour;
        $this->filterTwo = Test::double(
            new Filter('classFour', 'hookFour', 'method', 40, 4),
            [
                'getCallbackClosure' => function () use ($closureFour) {
                    return $closureFour;
                },
            ]
        )->getObject();
    }

    protected function setUpWordPressFunctionMocks()
    {
        $this->addActionMock = Test::func(__NAMESPACE__, 'add_action', true);
        $this->addFilterMock = Test::func(__NAMESPACE__, 'add_filter', true);
    }
}
