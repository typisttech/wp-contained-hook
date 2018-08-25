<?php

declare(strict_types=1);

namespace TypistTech\WPContainedHook\Test\Hooks;

use Codeception\Test\Unit;
use Mockery;
use Psr\Container\ContainerInterface;
use stdClass;
use TypistTech\WPContainedHook\ContainerAwareInterface;
use TypistTech\WPContainedHook\Hooks\Action;
use TypistTech\WPContainedHook\Test\ContainerAwareTestTrait;
use WP_Mock;

class ActionTest extends Unit
{
    use ContainerAwareTestTrait;

    /**
     * @var \TypistTech\WPContainedHook\Test\UnitTester
     */
    protected $tester;

    public function testRegister()
    {
        $action = new Action(
            'my-hook',
            'my-class-id',
            'my-callback-method',
            123,
            456
        );

        WP_Mock::userFunction('TypistTech\WPContainedHook\Hooks\add_action')
               ->with(
                   'my-hook',
                   [$action, 'run'],
                   '123',
                   456
               )
               ->once();

        $action->register();
    }

    public function testRunWithNoArgs()
    {
        $action = new Action(
            'my-hook',
            'my-class-id',
            'my-callback-method',
            123,
            456
        );

        $theClass = Mockery::mock();
        $theClass->expects('my-callback-method')
                 ->withNoArgs()
                 ->once();

        $container = Mockery::mock(ContainerInterface::class);
        $container->expects('get')
                  ->with('my-class-id')
                  ->andReturn($theClass)
                  ->once();

        $action->setContainer($container);

        $action->run();
    }

    public function testRunWithArgs()
    {
        $action = new Action(
            'my-hook',
            'my-class-id',
            'my-callback-method',
            123,
            456
        );

        $arg1 = new stdClass();
        $arg2 = new stdClass();

        $theClass = Mockery::mock();
        $theClass->expects('my-callback-method')
                 ->with($arg1, $arg2)
                 ->once();

        $container = Mockery::mock(ContainerInterface::class);
        $container->expects('get')
                  ->with('my-class-id')
                  ->andReturn($theClass)
                  ->once();

        $action->setContainer($container);

        $action->run($arg1, $arg2);
    }

    protected function getSubject(): ContainerAwareInterface
    {
        return new Action('a', 'b', 'c');
    }
}
