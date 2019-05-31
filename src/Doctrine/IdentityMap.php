<?php


namespace App\Doctrine;


use App\Logger;

class IdentityMap extends LoggedMap
{
    /** @var FunctionnalLogger */
    private $logger;

    private $data = [];

    public function __construct(FunctionnalLogger $logger, $input = array())
    {
        $this->logger = $logger;
        $this->data = $input;
    }

    public function offsetSet($index, $newval)
    {
        /**
        $this->logger->log('[identityMap] [class]', 'SET {key}', [
            '{key}' => $index,
        ]);
         */
        $this->data[$index] = $newval;
    }

    public function offsetGet($index)
    {
        $this->logger->get($index);

        if (class_exists($index)) {
            if (!isset($this->data[$index])) {
                $this->data[$index] = new IdentityMapObject(
                    new FunctionnalLogger($this->logger->getLogger(), FunctionnalLogger::IDENTIY_MAP_OBJECT, $this->logger->getResolveSplObjectHash()),
                    []
                );
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