<?php


namespace App\Doctrine;


use App\Logger;

class IdentityMap extends LoggedMap
{
    /** @var Logger */
    private $logger;

    private $data = [];

    public function __construct(Logger $logger, $input = array())
    {
        $this->logger = $logger;
        $this->data = $input;
    }

    public function offsetSet($index, $newval)
    {
        $this->logger->log('[identityMap] [class]', 'SET {key}', [
            '{key}' => $index,
        ]);
        $this->data[$index] = $newval;
    }

    public function offsetGet($index)
    {
        $this->logger->log('[identityMap] [class]', 'GET {key}', [
            '{key}' => $index,
        ]);

        if (class_exists($index)) {
            if (!isset($this->data[$index])) {
                $this->data[$index] = new IdentityMapObject($this->logger, []);
            }

            return $this->data[$index];
        }

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