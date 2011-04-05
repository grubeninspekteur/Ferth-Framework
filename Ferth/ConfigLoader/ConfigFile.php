<?php

namespace Ferth\ConfigLoader;

/**
 * Abstract class for file based configurations.
 *
 * @package Config
 */
abstract class ConfigFile implements \Ferth\Interfaces\ConfigLoader, \Ferth\Interfaces\ConfigLoader\ConfigFile
{
    protected $registry = array();

    /**
     * @var \Ferth\Interfaces\DIContainer
     */
    protected $container;
    
    /**
     * @var \Ferth\PriorityList
     */
    protected $dir_list;

    /**
     * Returns the file extension (e.g. 'ini').
     *
     * @return string
     */
    abstract protected function getFileExtension();

    /**
     * Loads the configuration, creates an ConfigObject, fills it with
     * data and returns it.
     *
     * @return \Ferth\Interfaces\ConfigObject
     */
    abstract protected function loadConfiguration($filename);

    public function __construct(\Ferth\Interfaces\DIContainer $container)
    {
        $this->container = $container;
        $this->dir_list = $container->getObject('Ferth\PriorityList');
    }

    public function configExists($config_name)
    {
       if (isset($this->registry[$config_name]))
       {
           return true;
       }

       return ($this->getFilename($config_name) !== null) ? true : false;
    }

    public function get($config_name, $throw_exception = true)
    {
        if (isset($this->registry[$config_name]))
        {
            return $this->registry[$config_name];
        }

        $filename = $this->getFilename($config_name);

        if ($filename === null)
        {
            if ($throw_exception)
            {
                // find out registered directories for display
                $directories = array();

                foreach($this->getDirList() as $dir)
                {
                    $directories[] = $dir;
                }
                throw new \Ferth\Exceptions\Configuration('Could not locate configuration ' . $config_name . '.' . $this->getFileExtension() .
                        ' in cascading directories ' . \implode(', ', $directories));
            } else {
                return null;
            }
        }

        return $this->registry[$config_name] = $this->loadConfiguration($filename);
    }

    /**
     * Returns the filename for this configuration.
     * 
     * @param string $config_name
     * 
     * @return string|null
     */
    protected function getFilename($config_name)
    {

        foreach ($this->getDirList() as $dir)
        {
            $filename = rtrim($dir, ' \\/') . \DIRECTORY_SEPARATOR . $config_name . '.' . $this->getFileExtension();
            if (is_file($filename))
            {
                return $filename;
            }
        }

        return null;
    }

    /**
     * Returns the directory list.
     *
     * @return \Ferth\PriorityList
     */
    public function getDirList()
    {
        return $this->dir_list;
    }
}
?>
