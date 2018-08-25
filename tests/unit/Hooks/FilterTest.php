<?php
declare(strict_types=1);

namespace TypistTech\WPContainedHook\Test\Hooks;

use Codeception\Test\Unit;
use Mockery;
use Psr\Container\ContainerInterface;
use stdClass;
use TypistTech\WPContainedHook\ContainerAwareInterface;
use TypistTech\WPContainedHook\Hooks\Filter;
use TypistTech\WPContainedHook\Test\ContainerAwareTestTrait;
use WP_Mock;

class FilterTest extends Unit
{
    use ContainerAwareTestTrait;

    /**
     * @var \TypistTech\WPContainedHook\Test\UnitTester
     */
    protected $tester;

    public function testRegister()
    {
        $action = new Filter(
            'my-hook',
            'my-class-id',
            'my-callback-method',
            123,
            456
        );

        WP_Mock::userFunction('TypistTech\WPContainedHook\Hooks\add_filter')
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
        $action = new Filter(
            'my-hook',
            'my-class-id',
            'my-callback-method',
            123,
            456
        );

        $expected = new stdClass();

        $theClass = Mockery::mock();
        $theClass->expects('my-callback-method')
                 ->withNoArgs()
                 ->andReturn($expected)
                 ->once();

        $container = Mockery::mock(ContainerInterface::class);
        $container->expects('get')
                  ->with('my-class-id')
                  ->andReturn($theClass)
                  ->once();

        $action->setContainer($container);

        $actual = $action->run();

        $this->assertSame($expected, $actual);
    }

    public function testRunWithArgs()
    {
        $action = new Filter(
            'my-hook',
            'my-class-id',
            'my-callback-method',
            123,
            456
        );

        $expected = new stdClass();
        $arg1 = new stdClass();
        $arg2 = new stdClass();

        $theClass = Mockery::mock();
        $theClass->expects('my-callback-method')
                 ->with($arg1, $arg2)
                 ->andReturn($expected)
                 ->once();

        $container = Mockery::mock(ContainerInterface::class);
        $container->expects('get')
                  ->with('my-class-id')
                  ->andReturn($theClass)
                  ->once();

        $action->setContainer($container);

        $actual = $action->run($arg1, $arg2);

        $this->assertSame($expected, $actual);
    }

    protected function getSubject(): ContainerAwareInterface
    {
        return new Filter('a', 'b', 'c');
    }
}
