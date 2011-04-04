<?php

namespace Ferth\Patterns;

/**
 * Implementation of the singleton pattern. You can chose any class you want.
 * The singleton object will always return the same instance of the class.
 * It works as a singleton factory, so it is possible to have multiple instances
 * of the same class.
 *
 * Example:
 *
 * <code>
 * $request_factory = new Ferth\Patterns\Singleton('Ferth\Request');
 * $request_factory = $singleton();
 * // or:
 * $request_factory->getInstance();
 * </code>
 *
 * If your class has parameters or needs additional setup, you have to use
 * a closure (it must be a closure, because callbacks could have been
 * misinterpreted as classnames).
 *
 * Example for constructor injection using the DIContainer:
 *
 * <code>
 * $cache_factory = new Ferth\Patterns\Singleton(
 * function() use($container) {
 *     return $container->getObject('Ferth\Cache\Memcached');
 * });
 * </code>
 *
 * Example for a config_factory with multi level files depending:
 *
 * <code>
 * $config_factory = new Ferth\Patterns\Singleton(
 * function () {
 *     $config = new Ferth\ConfigLoader\ConfigArray;
 *     $config->addDirectory('Configs/Debug', 100);
 *     $config->addDirectory('Configs', 0);
 *
 *     return $config;
 * });
 *
 * </code>
 *
 * @package patterns
 */
class Singleton
{
    protected $instance = null;
    protected $generation_value;

    /**
     * Constructs a new Singleton.
     *
     * @param string|Closure $generation_value A class name or a closure as a
     *                       factory.
     */
    public function __construct($generation_value)
    {
        $this->generation_value = $generation_value;
    }

    /**
     * Returns always the same instance of the registed object.
     * 
     * @return object
     */
    public function getInstance()
    {
        if (!isset($this->instance))
        {
            if ($this->generation_value instanceof \Closure)
            {
                $this->instance = call_user_func($this->generation_value);
            } else {
                $this->instance = new $this->generation_value;
            }
        }

        return $this->instance;
    }

    public function __invoke()
    {
        return $this->getInstance();
    }
}
?>
