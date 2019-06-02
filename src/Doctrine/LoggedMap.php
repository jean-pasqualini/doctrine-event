<?php


namespace App\Doctrine;

class LoggedMap implements \ArrayAccess, \IteratorAggregate
{
    /** @var FunctionnalLogger */
    private $logger;

    private $data = [];

    private $inbriqued;

    public function __construct(FunctionnalLogger $logger, array $input, bool $inbriqued = false)
    {
        $this->logger = $logger;
        $this->data = $input;
        $this->inbriqued = $inbriqued;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }


    public function offsetSet($index, $newval)
    {
        $this->logger->set($index, $newval);

        $this->data[$index] = $newval;
    }


    public function offsetGet($index)
    {
        $this->logger->get($index);

        if ($this->inbriqued) {
            if (!isset($this->data[$index])) {
                $this->data[$index] = [];
            }
            if (!$this->data[$index] instanceof LoggedMap) {
                $this->data[$index] = new self($this->logger, $this->data[$index], false );
            }
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

    public function getArrayCopy()
    {
        return $this->data;
    }

    public function &getOriginalArray()
    {
        return $this->data;
    }

}