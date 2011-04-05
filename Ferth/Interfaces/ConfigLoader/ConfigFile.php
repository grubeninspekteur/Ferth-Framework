<?php

namespace Ferth\Interfaces\ConfigLoader;

/**
 * Interface for ConfigFile.
 *
 * @package Config
 */
interface ConfigFile
{
    /**
     * Returns the directory list.
     *
     * @return \Ferth\PriorityList
     */
    public function getDirList();
}
?>