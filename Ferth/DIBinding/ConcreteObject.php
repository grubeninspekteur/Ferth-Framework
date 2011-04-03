<?php

namespace Ferth\DIBinding;

/**
 * Binding to a concrete object.
 *
 * @package DIContainer
 */
class ConcreteObject implements \Ferth\Interfaces\DIBinding
{
    /**
     * @var object $object
     */
    protected $object;

    public function __construct(\Ferth\Interfaces\DIContainer $container, $implementation)
    {
        $this->object = $implementation;
    }

    public function getObject(array $parameters = array())
    {
        return $this->object;
    }

    public function setParameters(array $parameters)
    {
        return;
        // it is not possible to set parameters
    }
}
?>
