<?php

namespace Ferth\ConfigLoader;

/**
 * The easiest and most common way to define a configuration. The file must
 * have the ending ".php" and return an array with the values set.
 *
 * Example of a configuration file:
 *
 * <code>
 * return array('name' => 'MyCompany', 'owner => 'Foo Bar');
 * </code>
 *
 * @package Config
 */
class ConfigArray extends ConfigFile
{
    protected function getFileExtension()
    {
        return 'php';
    }

    protected function loadConfiguration($filename)
    {
        $array = $this->getConfigArray($filename);

        if ($array === null) {
            $array = array();
        }

        return $this->container->getObject('Ferth\ConfigObject',
                array(0 => $array));

    }

    /**
     * Because the file will *return* an array, we have to encapsulate it
     * into another method.
     */
    protected function getConfigArray($filename)
    {
        // don't use once, it is against our "no hard coded singletons" rule
        return include($filename);
    }
}
?>
