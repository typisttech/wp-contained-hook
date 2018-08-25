<?php

declare(strict_types=1);

namespace TypistTech\WPContainedHook\Hooks;

use TypistTech\WPContainedHook\ContainerAwareInterface;

interface HookInterface extends ContainerAwareInterface
{
    /**
     * Add this hook to WordPress via one of:
     * - add_action
     * - add_filter
     * - WP_CLI::add_wp_hook (TODO)
     * - WP_CLI::add_hook (TODO)
     *
     * @return void
     */
    public function register(): void;

    /**
     * The actual callback that WordPress going to fire.
     *
     * @param mixed ...$args Arguments which pass on to the actual instance.
     *
     * @return mixed
     */
    public function run(...$args);
}
