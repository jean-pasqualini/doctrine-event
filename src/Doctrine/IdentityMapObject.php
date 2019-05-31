<?php


namespace App\Doctrine;


use App\Logger;

class IdentityMapObject implements \ArrayAccess
{
    /** @var Logger */
    private $logger;

    private $data = [];

    public function __construct(Logger $logger, $input = array())
    {
        $this->logger = $logger;
        $this->data = $input;
    }

    public function dumpScalar($scalar)
    {
        if (is_array($scalar)) {
            return 'array';
        }

        if (is_object($scalar)) {
            return 'object='.get_class($scalar).' (id='.$scalar->getId().')';
        }

        if (is_null($scalar)) {
            return 'NULL';
        }

        return gettype($scalar).'='.$scalar;
    }

    public function offsetSet($index, $newval)
    {
        $this->logger->log('[identityMap] [object]', 'SET {key} = {value}', [
            '{key}' => $index,
            '{value}' => $this->dumpScalar($newval),
        ]);
        $this->data[$index] = $newval;
    }

    public function offsetGet($index)
    {
        $this->logger->log('[identityMap] [object]', 'GET {key}', [
            '{key}' => $index,
        ]);

        return $this->data[$index];
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}