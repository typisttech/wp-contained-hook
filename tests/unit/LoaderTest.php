<?php
declare(strict_types=1);

namespace TypistTech\WPContainedHook\Test;

use Codeception\Test\Unit;
use Mockery;
use Psr\Container\ContainerInterface;
use TypistTech\WPContainedHook\ContainerAwareInterface;
use TypistTech\WPContainedHook\Hooks\Action;
use TypistTech\WPContainedHook\Hooks\Filter;
use TypistTech\WPContainedHook\Loader;

class LoaderTest extends Unit
{
    use ContainerAwareTestTrait;

    /**
     * @var \TypistTech\WPContainedHook\Test\UnitTester
     */
    protected $tester;

    public function testAdd()
    {
        $container = Mockery::mock(ContainerInterface::class);

        $loader = new Loader($container);

        $action = Mockery::mock(Action::class);
        $filter = Mockery::mock(Filter::class);

        $loader->add($action);
        $loader->add($action, $filter);

        $this->assertAttributeSame([
            $action,
            $filter,
        ], 'hooks', $loader);
    }

    public function testRun()
    {
        $container = Mockery::mock(ContainerInterface::class);

        $action = Mockery::mock(Action::class);
        $action->expects('setContainer')
               ->with($container)
               ->once();
        $action->expects('register')
               ->withNoArgs()
               ->once();

        $filter = Mockery::mock(Filter::class);
        $filter->expects('setContainer')
               ->with($container)
               ->once();
        $filter->expects('register')
               ->withNoArgs()
               ->once();

        $loader = new Loader($container);
        $loader->add($action, $filter);

        $loader->run();
    }

    protected function getSubject(): ContainerAwareInterface
    {
        $container = Mockery::mock(ContainerInterface::class);

        return new Loader($container);
    }
}
