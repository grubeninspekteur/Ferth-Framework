<?php

namespace Ferth\Interfaces;

/**
 * The ConfigLoader returns configuration objects.
 *
 * @todo enable caching
 *
 * @package Config
 */
interface ConfigLoader
{
   
    /**
     * Retrieves the config object. Throws an exception if the config does
     * not exist.
     *
     * @param string $config_name
     * @param boolean $throw_exception Whether to throw an exception (true)
     * or silently return null (false) if the specified config could not be
     * found.
     * 
     * @return \ArrayObject
     *
     * @throws Ferth\Exceptions\Configuration
     */
    public function get($config_name, $throw_exception = true);

    /**
     * Returns whether a config exists.
     *
     * @param string $config_name
     *
     * @return boolean
     */
    public function configExists($config_name);
}
?>
