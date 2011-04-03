<?php

namespace Ferth\Interfaces;

/**
 * Ferth autoloader interface
 *
 * @package Boot
 */
interface Autoloader
{
    const __CLASS = __CLASS__;
    public function __construct($base_dir);
    public function load($classname);
}
?>
