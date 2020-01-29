<?php

declare(strict_types=1);

namespace TypistTech\WPContainedHook\Hooks;

class Action extends AbstractHook
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        add_action(
            $this->hook,
            [$this, 'run'],
            $this->priority,
            $this->acceptedArgs
        );
    }

    /**
     * The actual callback that WordPress going to fire.
     *
     * @param mixed ...$args Arguments which pass on to the actual instance.
     *
     * @return void
     */
    public function run(...$args)
    {
        $container = $this->getContainer();
        $instance = $container->get($this->classIdentifier);

        $instance->{$this->callbackMethod}(...$args);
    }
}
