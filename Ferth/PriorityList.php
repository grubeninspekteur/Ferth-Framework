<?php

namespace Ferth;

/**
 * A priority list. The elements are sorted by a priority.
 *
 * @package 
 */
class PriorityList implements \Iterator
{
    protected $values = array();

    protected $priority = 0;
    protected $internal_position = 0;
    protected $valid = true;

    /**
     * Insert a value with a priority.
     *
     * @param mixed $value
     * @param int $priority
     */
    public function insert($value, $priority)
    {
        /**
         * We're using a multi-dimensional array here so different values
         * can share the same priority (with last in last out).
         */

        $this->values[$priority][] = $value;

        krsort($this->values);
    }

    public function rewind()
    {
        reset($this->values);
        $this->priority = key($this->values);
        $this->internal_position = 0;
        $this->valid = true;
    }

    public function current()
    {
        return $this->values[$this->priority][$this->internal_position];
    }

    public function next()
    {
        $this->internal_position++;

        if (!isset($this->values[$this->priority][$this->internal_position]))
        {
            $this->internal_position = 0;

            if (false === next($this->values))
            {
                $this->valid = false;
            } else {
                $this->priority = key($this->values);
            }
        }
    }
    
    public function valid()
    {
        return ($this->valid && isset($this->values[$this->priority][$this->internal_position]));
    }

    public function key()
    {
        return key($this->values);
    }
}
?>
