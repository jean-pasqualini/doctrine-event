<?php


namespace App\Doctrine;


use App\Logger;

class LoggedMap implements \ArrayAccess, \IteratorAggregate
{
    /** @var Logger */
    private $logger;

    private $data = [];

    private $type;

    private $dumpScalar;

    private $inbriqued;

    private $resolveSplObjectHash;

    public function __construct(Logger $logger, array $input, string $type, bool $dumpScalar = false, bool $inbriqued = false, ResolveSplObjectHash $resolveSplObjectHash = null)
    {
        $this->type = $type;
        $this->logger = $logger;
        $this->data = $input;
        $this->dumpScalar = $dumpScalar;
        $this->inbriqued = $inbriqued;
        $this->resolveSplObjectHash = $resolveSplObjectHash;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    public function dumpScalar($scalar)
    {
        if (is_array($scalar)) {
            if ($this->dumpScalar) {
                return 'array ('.json_encode($scalar).')';
            }

            return 'array';
        }

        if (is_object($scalar)) {
            return 'object=' . get_class($scalar) . ' (id=' . $scalar->getId() . ')';
        }

        if (is_null($scalar)) {
            return 'NULL';
        }

        return gettype($scalar) . '=' . $scalar;
    }

    public function offsetSet($index, $newval)
    {
        $this->logger->log($this->type, 'SET {key} = {value}', [
            '{key}' => $this->resolveIndex($index),
            '{value}' => $this->dumpScalar($newval),
        ]);
        $this->data[$index] = $newval;
    }

    protected function resolveIndex($index)
    {
        if (null === $this->resolveSplObjectHash || strrpos($index, '000000') === false) {
            return $index;
        }

        $resolvedObject = $this->resolveSplObjectHash->resolve($index);

        if (null === $resolvedObject) {
            return $index. '(unresolved)';
        }

        return $this->dumpScalar($resolvedObject);
    }


    public function offsetGet($index)
    {
        $this->logger->log($this->type, 'GET {key}', [
            '{key}' => $this->resolveIndex($index),
        ]);

        if ($this->inbriqued) {
            if (!isset($this->data[$index])) {
                $this->data[$index] = [];
            }
            if (!$this->data[$index] instanceof LoggedMap) {
                $this->data[$index] = new self($this->logger, $this->data[$index], $this->type.' [imbriqued]', $this->dumpScalar, $this->inbriqued );
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