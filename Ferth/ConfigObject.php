<?php
namespace Ferth;

/**
 * The ConfigObject holds the variables of one configuration group.
 *
 * Retrieving example:
 * <code>
 * // Assuming that $config is an instance of ConfigLoader
 *
 * // Direct variable access (read + write)
 * $config('main')->$project_name;
 *
 * // Access via Array
 * $config_main = $config('main');
 * $config_main['project_name'];
 * </code>
 *
 * @package Config
 */
class ConfigObject extends \ArrayObject implements Interfaces\ConfigObject
{
    public function __construct(array $elements = array())
    {
        foreach($elements as $key => $value)
        {
            $this->$key = $value;
        }
    }
}
?>
