<?php

namespace Ferth\DIBinding;

/**
 * Binding to a classname.
 *
 * @package DIContainer
 */
class Classname extends WithReflection implements \Ferth\Interfaces\DIBinding
{
    /**
     * @var DIContainer
     */
    protected $container;

    protected $classname;

    public function __construct(\Ferth\Interfaces\DIContainer $container, $implementation)
    {
        $this->container = $container;
        $this->classname = $implementation;
    }

    public function getObject(array $parameters = array())
    {
        // Overwrite existing parameters with specific ones.
        $parameters = array_replace($this->parameters, $parameters);

        if (!class_exists($this->classname))
        {
            throw new \InvalidArgumentException('Bound class '. $this->classname . ' does not exist');
        }

        if (method_exists($this->classname, '__construct'))
        {
            $reflection = new \ReflectionMethod($this->classname, '__construct');

            /**
             * ReflectionMethod has $class as the class where the method
             * was defined in. We need the real one.
             */

            $reflection->late_bound_class = $this->classname;

            $parameters = $this->getResolvedParameters($reflection, $parameters);

            // Use another reflection to use dynamic constructor calling
            $reflection = new \ReflectionClass($this->classname);
            return $reflection->newInstanceArgs($parameters);
        } else {
            return new $this->classname;
        }
    }

    protected function expectedParameterException($position, $name)
    {
        throw new \BadMethodCallException('For the constructor of class '
                        . $this->classname
                        . ' is no value registered for parameter '
                        . $position
                        . ', named '
                        . $name
                        . '. It is also not type hinted, so no object could have been created.');
    }
}
?>
