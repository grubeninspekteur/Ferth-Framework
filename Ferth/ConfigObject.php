<?php

namespace Ferth;

/**
 * Description of ConfigObject
 *
 * @package 
 */
class ConfigObject extends \ArrayObject
{
    public function __construct(array $array)
    {
        parent::__construct($array, \ArrayObject::ARRAY_AS_PROPS);
    }
}
?>
