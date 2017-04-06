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

use Closure;
use Psr\Container\ContainerInterface;

/**
 * Final class Filter
 *
 * Data transfer object that holds WordPress filter information.
 */
final class Filter extends AbstractHook
{
    const ID_PREFIX = 'filter';

    /**
     * Callback closure getter.
     *
     * The actual callback that WordPress going to fire.
     *
     * @param ContainerInterface $container The container.
     *
     * @return Closure
     */
    public function getCallbackClosure(ContainerInterface $container): Closure
    {
        return function (...$args) use ($container) {
            $instance = $container->get($this->classIdentifier);

            return $instance->{$this->callbackMethod}(...$args);
        };
    }
}
