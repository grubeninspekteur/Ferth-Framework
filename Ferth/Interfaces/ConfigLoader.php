<?php

namespace Ferth\Interfaces;

/**
 * The ConfigLoader returns configuration objects.
 *
 * @package Config
 */
interface ConfigLoader
{
    const __CLASS = __CLASS__;
    
    /**
     * Retrieves the config object. Throws an exception if the config does
     * not exist.
     *
     * @param string $config_name
     * @param boolean $throw_exception Whether to throw an exception (true)
     * or silently return null (false).
     * @throws Ferth\Exceptions\Configuration
     * @return ConfigObject
     */
    public function __invoke($config_name, $throw_exception = true);
}
?>
