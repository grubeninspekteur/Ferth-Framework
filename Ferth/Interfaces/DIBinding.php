<?php

namespace Ferth\Interfaces;

/**
 * Interface for bindings in the DIContainer.
 *
 * @package DIContainer
 */
interface DIBinding
{
    /**
     * @param DIContainer $container The container in which the binding is
     *        registered.
     * @param mixed $implementation The concrete implementation (e.g. a class
     *        name).
     */
    public function __construct(DIContainer $container, $implementation);

    /**
     * Returns an object of this concrete binding.
     *
     * @return object
     */
    public function getObject(array $parameters = array());

    /**
     * Set additional parameters for the implementation.
     *
     * @param array $parameters Parameters in the form position => value OR
     * name => value.
     */
    public function setParameters(array $parameters);
}
?>