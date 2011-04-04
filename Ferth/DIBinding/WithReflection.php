<?php

namespace Ferth\DIBinding;

/**
 * Provides methods for reflection.
 *
 * @package DIContainer
 */
abstract class WithReflection
{
    protected $parameters = array();

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    protected function getResolvedParameters(\ReflectionFunctionAbstract $reflection, array $parameters_i)
    {
        $parameters = array();
        
        foreach ($reflection->getParameters() as $position => $parameter)
        {
            if (isset($parameters_i[$position]))
            {
                $parameters[$position] = $parameters_i[$position];
                continue;
            }

            if (isset($parameters_i[$parameter->getName()]))
            {
                $parameters[$position] = $parameters_i[$parameter->getName()];
                continue;
            }

            if ($parameter->isDefaultValueAvailable())
            {
                continue;
            }

            // Only type hinted variables can be injected (we don't support annotation programming)
            if ($parameter->isArray())
            {
                $this->expectedParameterException($position, $parameter->getName());
            }

            $interface = $parameter->getClass();

            if ($interface === null)
            {
                $this->expectedParameterException($position, $parameter->getName());
            }

            // Now create an object using the container

            // check whether tags have to be checked
            if ($reflection instanceof \ReflectionMethod && $reflection->isConstructor())
            {
                /*/**
                 * Note: late_bound_class is not a real reflection feature; we
                 * set it earlier to get the "real" class; otherwise we will
                 * get the class in which the method has been implemented.
                 */
                $parameters[$position] = $this->container->getObjectConsideringTags($interface->getName(), class_implements($reflection->late_bound_class));
            } else {
                $parameters[$position] = $this->container->getObject($interface->getName());
            }
        }

        return $parameters;
    }

    abstract protected function expectedParameterException($position, $name);
}
?>
