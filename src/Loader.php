<?php
/**
 * WP Contained Hook
 *
 * Lazily instantiate objects from dependency injection container
 * to WordPress hooks (actions and filters).
 *
 * @package   TypistTech\WPContainedHook
 * @author    Typist Tech <wp-contained-hook@typist.tech>
 * @copyright 2017 Typist Tech
 * @license   MIT
 * @see       https://www.typist.tech/projects/wp-contained-hook
 */

declare(strict_types=1);

namespace TypistTech\WPContainedHook;

use Psr\Container\ContainerInterface as Container;

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 */
final class Loader
{
    /**
     * Array of actions registered with WordPress.
     *
     * @var Action[]
     */
    private $actions;

    /**
     * The container.
     *
     * @var Container
     */
    private $container;

    /**
     * Array of filters registered with WordPress.
     *
     * @var Filter[]
     */
    private $filters;

    /**
     * Initialize the collections used to maintain the actions and filters.
     *
     * @param Container $container The container.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->actions   = [];
        $this->filters   = [];
    }

    /**
     * Add new actions to the collection to be registered with WordPress.
     *
     * @param Action|Action[] ...$actions Actions to be registered.
     *
     * @return void
     */
    public function addAction(Action ...$actions)
    {
        $this->actions = array_unique(
            array_merge($this->actions, $actions),
            SORT_REGULAR
        );
    }

    /**
     * Add new filters to the collection to be registered with WordPress.
     *
     * @param Filter|Filter[] ...$filters Filters to be registered.
     *
     * @return void
     */
    public function addFilter(Filter ...$filters)
    {
        $this->filters = array_unique(
            array_merge($this->filters, $filters),
            SORT_REGULAR
        );
    }

    /**
     * Register the filters and actions with WordPress.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->actions as $action) {
            add_action(
                $action->getHook(),
                $action->getCallbackClosure($this->container),
                $action->getPriority(),
                $action->getAcceptedArgs()
            );
        }

        foreach ($this->filters as $filter) {
            add_filter(
                $filter->getHook(),
                $filter->getCallbackClosure($this->container),
                $filter->getPriority(),
                $filter->getAcceptedArgs()
            );
        }
    }
}
