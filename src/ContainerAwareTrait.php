<?php
declare(strict_types=1);

namespace TypistTech\WPContainedHook;

use Psr\Container\ContainerInterface;
use TypistTech\WPContainedHook\Exceptions\ContainerException;

trait ContainerAwareTrait
{
    /**
     * The container instance.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Get the container.
     *
     * @return ContainerInterface
     *
     * @throws ContainerException If no container implementation has been set.
     */
    public function getContainer(): ContainerInterface
    {
        if ($this->container instanceof ContainerInterface) {
            return $this->container;
        }

        throw new ContainerException('No container implementation has been set.');
    }

    /**
     * Set a container.
     *
     * @param ContainerInterface $container The container instance.
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }
}