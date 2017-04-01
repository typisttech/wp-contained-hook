<?php
/**
 * WP CloudFlare Guard
 *
 * Connecting WordPress with Cloudflare firewall,
 * protect your WordPress site at DNS level.
 * Automatically create firewall rules to block dangerous IPs.
 *
 * @package   WPCFG
 * @author    Typist Tech <wp-cloudflare-guard@typist.tech>
 * @copyright 2017 Typist Tech
 * @license   GPL-2.0+
 * @see       https://www.typist.tech/projects/wp-cloudflare-guard
 * @see       https://wordpress.org/plugins/wp-cloudflare-guard/
 */

declare(strict_types=1);

namespace TypistTech\WPContainedHook;

use Closure;
use Psr\Container\ContainerInterface;

/**
 * Final class Action
 *
 * Data transfer object that holds WordPress action information.
 */
final class Action extends AbstractHook
{
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
            $instance->{$this->callbackMethod}(...$args);
        };
    }
}
