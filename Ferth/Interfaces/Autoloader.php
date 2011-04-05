<?php

namespace Ferth\Interfaces;

/**
 * Ferth autoloader interface
 *
 * @package Boot
 */
interface Autoloader
{
    
    public function __construct($base_dir);
    public function load($classname);
}
?>
