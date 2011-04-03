<?php

namespace Ferth\Interfaces;


/**
 * Interface for ConfigObjects. The listed interfaces are all fulfilled by the
 * SPL ArrayObject.
 */
interface ConfigObject extends \ArrayAccess, \Traversable, \IteratorAggregate, \Serializable, \Countable
{
    const __CLASS = __CLASS__;
    
    /**
     * You can fill the ConfigObject with values by passing an elements
     * array.
     *
     * @param Array $elements
     */
    public function __construct(Array $elements = array());
}
?>
