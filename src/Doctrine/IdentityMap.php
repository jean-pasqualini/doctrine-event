<?php

declare(strict_types=1);

namespace App\Doctrine;

use function count;
use Doctrine\ORM\UnitOfWork;
use function get_class;
use function serialize;

/**
 * Class for maintaining an object identity map.
 */
class IdentityMap
{
    /** @var object[][] */
    private $identityMap = [];
    /**
     * @var FunctionnalLogger
     */
    private $functionnalLogger;
    /**
     * @var UnitOfWork
     */
    private $unitOfWork;

    private static $instances = 0;

    public function __construct(FunctionnalLogger $functionnalLogger, UnitOfWork $unitOfWork)
    {
        $this->functionnalLogger = $functionnalLogger;
        $this->unitOfWork = $unitOfWork;
        self::$instances++;
    }

    /**
     * @param object $object
     */
    public function contains($object, $objectIdentifier) : bool
    {
        $className = get_class($object);

        return isset($this->identityMap[$className][$objectIdentifier]);
    }

    /**
     * @param object $object
     */
    public function hasObjectIdentifier($className, $objectIdentifier) : bool
    {
        return isset($this->identityMap[$className][$objectIdentifier]);
    }

    /**
     * @param object $object
     */
    public function getObject($className, $objectIdentifier)
    {
        return $this->identityMap[$className][$objectIdentifier];
    }

    /**
     * @param object $object
     */
    public function setObject($className, $objectIdentifier) : bool
    {
        return $this->identityMap[$className][$objectIdentifier];
    }

    public function removeObject($className, $objectIdentifier)
    {
        unset($this->identityMap[$className][$objectIdentifier]);
    }

    /**
     * @param mixed[] $data
     *
     * @return object|null
     */
    public function tryGetById(string $className, $objectIdentifier)
    {
        $objectIdentifier = implode(' ', (array) $objectIdentifier);
        $this->functionnalLogger->get([$className, $objectIdentifier]);

        if (isset($this->identityMap[$className][$objectIdentifier])) {
            return $this->identityMap[$className][$objectIdentifier];
        }

        return false;
    }

    /**
     * @param object  $object
     * @param mixed[] $data
     */
    public function addToIdentityMap($object)
    {
        $rootName = $this->unitOfWork->getRootEntityName($object);
        $identifiersHash = $this->unitOfWork->getEntityIdentifierHash($object);

        $this->addComplexToIdentityMap($rootName, $identifiersHash, $object);
        $this->functionnalLogger->set($identifiersHash, $object);
    }

    /**
     * @param object  $object
     * @param mixed[] $data
     */
    public function addComplexToIdentityMap($rootName, $objectIdentifier, $object)
    {
        $objectIdentifier = implode(' ', (array) $objectIdentifier);
        $this->identityMap[$rootName][$objectIdentifier] = $object;

        $this->functionnalLogger->set($objectIdentifier, $object);
    }

    public function count() : int
    {
        return count($this->identityMap);
    }

    public function toArray()
    {
        return $this->identityMap;
    }
}