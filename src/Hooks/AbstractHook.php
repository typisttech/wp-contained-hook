<?php

declare(strict_types=1);

namespace TypistTech\WPContainedHook\Hooks;

use TypistTech\WPContainedHook\ContainerAwareTrait;

abstract class AbstractHook implements HookInterface
{
    use ContainerAwareTrait;

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
     * @param string $hook            The name of the WordPress hook that is being registered.
     * @param string $classIdentifier Identifier of the entry to look for from container.
     * @param string $callbackMethod  The callback method name.
     * @param int    $priority        Optional.The priority at which the function should be fired. Default is 10.
     * @param int    $acceptedArgs    Optional. The number of arguments that should be passed to the $callback.
     *                                Default is 1.
     */
    public function __construct(
        string $hook,
        string $classIdentifier,
        string $callbackMethod,
        $priority = null,
        $acceptedArgs = null
    ) {
        $this->hook = $hook;
        $this->classIdentifier = $classIdentifier;
        $this->callbackMethod = $callbackMethod;
        $this->priority = (int) ($priority ?? 10);
        $this->acceptedArgs = (int) ($acceptedArgs ?? 1);
    }
}
