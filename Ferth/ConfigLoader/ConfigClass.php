<?php

namespace Ferth\ConfigLoader;

/**
 * Loads configurations based on config classes which implement the
 * ConfigObject interface.
 *
 * Using ConfigClasses is the simplest way of using a configuration.
 *
 * @package Config
 */
class ConfigClass implements \Ferth\Interfaces\ConfigLoader
{
    protected $registry = array();
    
    protected $config_namespace;

    /**
     *
     * @param string $config_namespace The namespace where the config files
     * rest
     */
    public function __construct($config_namespace)
    {
        $this->config_namespace = $config_namespace;
    }

    public function __invoke($config_name, $throw_exception = true)
    {
        if (isset($registry[$config_name]))
        {
            return $registry[$config_name];
        }

        // Not yet in registry, so load the configuration class

        $classname = $config_namespace . '\\' . $config_name;

        if (!class_exists($classname))
        {
            if ($throw_exception === true)
            {
                /**
                 * @todo Pass message through I18n class
                 */
                throw new \Ferth\Exceptions\Configuration("Could not locate configuration $config_name in namespace {$this->config_namespace}");
            } else {
                return null;
            }
        }

        $registry[$config_name] = new $classname;

        return $registry[$config_name];
    }
}
?>
