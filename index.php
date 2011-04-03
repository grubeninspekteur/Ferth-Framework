<?php
/**
 * The application's central entry point
 *
 * This is the only php file a visitor should be able to access. It includes
 * and registers the Ferth autoloader and proceeds with the application's
 * bootstrap class (if there is any), which implements the bootstrap
 * interface.
 *
 * You should not have to change anything in this file.
 *
 * @package boot
 */

use \Ferth;

/**
 * Load the autoloader.
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Ferth' . DIRECTORY_SEPARATOR . 'Autoloader.php';

$autoloader = new Ferth\Autoloader(__DIR__);

spl_autoload_register(array($autoloader, 'load'));

/**
 * Execute the bootstrap.
 */

if (class_exists('App\Bootstrap'))
{
    App\Bootstrap::init();
    App\Bootstrap::execute();
} else {
    Ferth\Bootstrap::init();
    Ferth\Bootstrap::execute();
}
?>