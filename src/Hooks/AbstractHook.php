<?php
declare(strict_types=1);

namespace TypistTech\WPContainedHook\Hooks;

use TypistTech\WPContainedHook\ContainerAwareTrait;

abstract class AbstractHook implements HookInterface
{
    use ContainerAwareTrait;

    protected const ID_PREFIX = self::ID_PREFIX;

    /**
     * The number of arguments that should be passed to the $callback.
     *
     * @var int
     */
    protected $acceptedArgs;

    /**
     * The callback method name.
     *
     * @var string
     */
    protected $callbackMethod;

    /**
     * Identifier of the entry to look for from container.
     *
     * @var string
     */
    protected $classIdentifier;

    /**
     * The name of the WordPress hook that is being registered.
     *
     * @var string
     */
    protected $hook;

    /**
     * The priority at which the function should be fired.
     *
     * @var int
     */
    protected $priority;

    /**
     * Filter constructor.
     *
     * @param string   $hook            The name of the WordPress hook that is being registered.
     * @param string   $classIdentifier Identifier of the entry to look for from container.
     * @param string   $callbackMethod  The callback method name.
     * @param int|null $priority        Optional.The priority at which the function should be fired. Default is 10.
     * @param int|null $acceptedArgs    Optional. The number of arguments that should be passed to the $callback.
     *                                  Default is 1.
     */
    public function __construct(
        string $hook,
        string $classIdentifier,
        string $callbackMethod,
        int $priority = null,
        int $acceptedArgs = null
    ) {
        $this->hook = $hook;
        $this->classIdentifier = $classIdentifier;
        $this->callbackMethod = $callbackMethod;
        $this->priority = $priority ?? 10;
        $this->acceptedArgs = $acceptedArgs ?? 1;
    }

    /**
     * Add this hook to WordPress via:
     * - add_action
     * - add_filter
     * - WP_CLI::add_wp_hook
     * - WP_CLI::add_hook
     *
     * @return void
     */
    abstract public function register(): void;

    /**
     * The actual callback that WordPress going to fire.
     *
     * @param mixed ...$args Arguments which pass on to the actual instance.
     *
     * @return mixed
     */
    abstract public function run(...$args);
}
