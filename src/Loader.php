<?php
declare(strict_types=1);

namespace TypistTech\WPContainedHook;

use Psr\Container\ContainerInterface;
use TypistTech\WPContainedHook\Hooks\HookInterface;

/**
 * Register all actions and filters for the plugin/package/theme.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 */
class Loader implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Array of hooks registered with WordPress.
     *
     * @var HookInterface[]
     */
    protected $hooks = [];

    /**
     * Initialize the collections used to maintain the hooks.
     *
     * @param ContainerInterface $container The container.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Add new hooks to the collection to be registered with WordPress.
     *
     * @param HookInterface|HookInterface[] ...$hooks Hooks to be registered.
     *
     * @return void
     */
    public function add(HookInterface ...$hooks): void
    {
        $this->hooks = array_values(
            array_unique(
                array_merge($this->hooks, $hooks),
                SORT_REGULAR
            )
        );
    }

    /**
     * Register the hooks to the container and WordPress.
     *
     * @return void
     */
    public function run(): void
    {
        foreach ($this->hooks as $hook) {
            $hook->setContainer($this->container);
            $hook->register();
        }
    }
}
