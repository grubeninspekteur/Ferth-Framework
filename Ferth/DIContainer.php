<?php

namespace Ferth;

/**
 * Implementation of {@see \Ferth\Interfaces\DIContainer}. Read the interface's
 * description to learn more about this class.
 *
 * @package DIContainer
 */
class DIContainer implements Interfaces\DIContainer
{
    /**
     * @var array The current binding as 0 => name, 1 => DIBinding
     */
    protected $current_binding = null;
    protected $current_tag = null;

    protected $parent = null;

    /**
     *
     * @var array An Array of the registered bindings. TagBindings will be
     *            registered as name.tag.
     */
    protected $bindings;

    public function bindCallback($name, $callback)
    {
        if(!is_callable($callback, true))
        {
            throw new Exceptions\DIContainer('The registered callback ' . var_export($callback, true)
                    . ' is not valid in syntax. Check the PHP documentation for valid callback definitions.');
        }

        $this->current_binding = array(trim($name, ' \\'), new DIBinding\Callback($this, $callback));
        return $this;
    }

    public function bindImplementation($name, $implementation)
    {
        if (is_object($implementation))
        {
            $this->current_binding = array(trim($name, ' \\'), new DIBinding\ConcreteObject($this, $implementation));
            return $this;
        }

        $this->current_binding = array(trim($name, ' \\'), new DIBinding\Classname($this, $implementation));
        return $this;
    }

    public function createChild()
    {
        $child = new DIContainer();
        $child->setParent($this);

        return $child;
    }

    public function forTag($interface_name)
    {
        $this->current_tag = trim($interface_name, ' \\');
        
        return $this;
    }

    public function save()
    {
        if (!isset($this->current_binding))
        {
            throw new \BadMethodCallException('DIContainer::save() may not be called before bindCallback() or bindImplementation()');
        }

        list($name, $binding) = $this->current_binding;

        if (isset($this->current_tag))
        {
            $name .= '.' . $this->current_tag;
        }

        $this->bindings[$name] = $binding;

        $this->current_binding = $this->current_tag = null;
    }

    public function getObject($name, array $parameters = array())
    {
        $name = trim($name, ' \\');
        if (isset($this->bindings[$name]))
        {
            try {
                $object = $this->bindings[$name]->getObject($parameters);
            } catch (Exception $e) {
                throw new Exceptions\DIContainer(get_class($this->bindings[$name])
                        . ' threw an ' . get_class($e)
                        . ' with the message ' . $e->message);
            }

            return $object;
        }

        // Ask the parent for a binding
        if (isset($this->parent))
        {
            return $object = $this->parent->getObject($name, $parameters);
        }

        // No binding registered, try instantiating a normal class
        if (class_exists($name))
        {
            $binding = new DIBinding\Classname($this, $name);
            return $binding->getObject($parameters);
        }

        // No binding found, throw an exception
        throw new Exceptions\DIContainer('No appropiate class has been registered for ' . $name . ' or the class does not exist', self::NO_BINDING_FOUND);
    }

    public function getObjectConsideringTags($name, array $interfaces, array $parameters = array())
    {
        foreach($interfaces as $tag)
        {
            if (!isset($this->bindings[$name . '.' . $tag]))
            {
                continue;
            } else {
                return $this->getObject($name . '.' . $tag, $parameters);
            }
        }

        // No specific tag variants has been implemented
        return $this->getObject($name, $parameters);
    }
    
    public function setParent(Interfaces\DIContainer $container)
    {
        $this->parent = $container;
    }

    public function withParameters(array $parameters)
    {
        if (!isset($this->current_binding))
        {
            throw new \BadMethodCallException('DIContainer::withParameters() may not be called before bindCallback() or bindImplementation()');
        }

        $this->current_binding[1]->setParameters($parameters);

        return $this;
    }
}
?>
