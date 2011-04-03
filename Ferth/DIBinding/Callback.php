<?php

namespace Ferth\DIBinding;

/**
 * Binding to a callback or anonymous function.
 *
 * @package DIContainer
 */
class Callback  extends WithReflection implements \Ferth\Interfaces\DIBinding
{
    /**
     * @var DIContainer
     */
    protected $container;

    /**
     * @var callback
     */
    protected $callback;

    public function __construct(\Ferth\Interfaces\DIContainer $container, $implementation)
    {
        $this->container = $container;
        $this->callback = $implementation;
    }

    public function getObject(array $parameters = array())
    {
        // Overwrite existing parameters with specific ones.
        $parameters = array_replace($this->parameters, $parameters);
        
        if (!is_callable($this->callback))
        {
            throw new \InvalidArgumentException('Registered binding ' . var_export($this->callback, true) . ' is not a valid callback');
        }

        // Find out if more objects / parameters are required

        /**
         * @var ReflectionFunctionAbstract
         */
        $reflection = \Ferth\ReflectionFunctionFactory::getReflectionFunction($this->callback);

        $parameters = $this->getResolvedParameters($reflection, $parameters);

        return \call_user_func_array($this->callback, $parameters);
    }

    protected function expectedParameterException($position, $name)
    {
        throw new \BadMethodCallException('For the callback '
                        . var_export($this->callback, true)
                        . ' is no value registered for parameter '
                        . $position
                        . ', named '
                        . $name
                        . '. It is also not type hinted, so no object could have been created.');
    }
}
?>
