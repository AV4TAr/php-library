<?php
/**
 * Created by IntelliJ IDEA.
 * User: fsilva
 * Date: 5/28/18
 * Time: 18:58
 */

namespace We\DataHub;


use Exception;
use Iterator;
use JsonSerializable;

/**
 * Represents a valid set of fields.
 *
 *  Fields are (key, value) pairs where both elements are strings and only
 *  the value is nullable.
 *
 * @package We\DataHub
 *
 */
class Fields implements Iterator, JsonSerializable
{

    private $position = 0;
    private $fields = array();

    public function __construct()
    {
        $this->position = 0;
    }

    /**
     * Append fields (name, value) to the collection enforcing the key and value types.
     *
     * @param string $fieldName
     * @param null|string $value
     * @return Fields
     * @throws Exception
     *
     */
    public function append(string $fieldName, ?string $value): Fields
    {
        $this->fields[$fieldName] = $value;
        return $this;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->fields[$this->position];

    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->fields[$this->position]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->position = 0;
    }

    public function jsonSerialize()
    {
        // return only the things we want to serialize.
        // in this case $position, for example, is irrelevant.
        return $this->fields;
    }

}