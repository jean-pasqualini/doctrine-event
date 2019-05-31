<?php


namespace App\Doctrine;


class ResolveSplObjectHash
{
    /**
     * @var LoggedMap
     */
    private $entityIdentifier;
    /**
     * @var LoggedMap
     */
    private $identityMap;

    public function __construct(LoggedMap $entityIdentifier, LoggedMap $identityMap)
    {
        $this->entityIdentifier = $entityIdentifier;
        $this->identityMap = $identityMap;
    }

    public function resolve($index)
    {
        $entityIdentifier = $this->entityIdentifier->getOriginalArray();

        if (!isset($entityIdentifier[$index])) {
            return null;
        }

        $id = $entityIdentifier[$index]['id'];

        return null;
    }
}