<?php

/**
 * Bootstrap interface
 *
 * @package Boot
 */

namespace Ferth\Interfaces;

interface Bootstrap
{
    
    /**
     * Create all necessary dependencies.
     */
    public static function init();

    /**
     * Dispatch request.
     */
    public static function execute();
}
?>
