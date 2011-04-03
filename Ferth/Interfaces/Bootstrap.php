<?php

/**
 * Bootstrap interface
 *
 * @package Boot
 */

namespace Ferth\Interfaces;

interface Bootstrap
{
    const __CLASS = __CLASS__;
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
