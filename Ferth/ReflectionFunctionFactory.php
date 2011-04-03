<?php

namespace Ferth;

/**
 * Determines wheter a ReflectionFunction or ReflectionObject should be
 * created.
 *
 * @author uramihsayibok@gmail.com
 * @link http://de3.php.net/manual/de/class.reflectionfunction.php#100570
 * @license CC By 3.0
 *
 * @package Vendor
 */
class ReflectionFunctionFactory
{
    public static function getReflectionFunction($callback) {
    if (is_array($callback)) {

        if (count($callback) != 2)
        {
            throw new \InvalidArgumentException('Expected array $callback to have exactly 2 parameters. Use string instead');
        }
        
        // must be a class method
        list($class, $method) = $callback;
        return new \ReflectionMethod($class, $method);
    }

    // class::method syntax
    if (is_string($callback) && strpos($callback, "::") !== false) {
        list($class, $method) = explode("::", $callback);
        return new \ReflectionMethod($class, $method);
    }

    // objects as functions (PHP 5.3+)
    if (method_exists($callback, "__invoke")) {
        return new \ReflectionMethod($callback, "__invoke");
    }

    // assume it's a function
    return new \ReflectionFunction($callback);
}
}
?>
