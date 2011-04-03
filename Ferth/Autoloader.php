<?php

/**
 * Namespace driven autoloader
 *
 * @package Boot
 */

namespace Ferth;

require_once 'Interfaces' . DIRECTORY_SEPARATOR . 'Autoloader.php';

class Autoloader implements Interfaces\Autoloader
{
    protected $base_dir;
    
    /**
     *
     * @param string $base_dir The base dir from which the namespaces should
     * be resolved as a path
     */
    public function __construct($base_dir)
    {
        $this->base_dir = $base_dir;
    }

    /**
     * Includes the appropriate file containing the class $class_name.
     *
     * @param string $class_name fully qualified class name. Note that
     * spl_autoload provides them automatically.
     */
    public function load($class_name)
    {
        // replace backslashes for unix based operating systems
        $filename = $this->base_dir . \DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';

        if (!file_exists($filename)) return false;


        require $filename;

        return true;
    }
}
?>
