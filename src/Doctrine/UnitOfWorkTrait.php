<?php


namespace App\Doctrine;


use App\Logger;
use Doctrine\ORM\UnitOfWork;

trait UnitOfWorkTrait
{
    private $resoverSplObjectHash;
    private $spyLogger;

    private function spyArray($property, $type, $imbriqued)
    {
        if (UnitOfWork::WRAP_ARRAY[$type] && !$this->{$property} instanceof LoggedMap) {
            $this->{$property} = new LoggedMap(
                new FunctionnalLogger(
                    $this->getSpyLogger(),
                    $type,
                    $this->getResolverSplObjectHash()
                ),
                $this->{$property},
                $imbriqued
            );
        }
    }

    private function getResolverSplObjectHash()
    {
        if (null === $this->resoverSplObjectHash) {
            $this->resoverSplObjectHash = new ResolveSplObjectHash($this);
        }

        return $this->resoverSplObjectHash;
    }

    private function getSpyLogger()
    {
        if (null === $this->spyLogger) {
            $this->spyLogger = new Logger();
        }

        return $this->spyLogger;
    }

    public function wrapArray()
    {
        $resoverSplObjectHash = new ResolveSplObjectHash($this);
        $logger = new Logger();

        if (UnitOfWork::WRAP_ARRAY[FunctionnalLogger::IDENTIY_MAP_CLASS] && !$this->identityMap instanceof IdentityMap) {
            $this->identityMap = new IdentityMap(
                new FunctionnalLogger($logger, FunctionnalLogger::IDENTIY_MAP_CLASS, $resoverSplObjectHash),
                $this
            );
        }

        $this->spyArray('entityIdentifiers', FunctionnalLogger::ENTITY_IDENTIFIER, false);
        $this->spyArray('originalEntityData', FunctionnalLogger::ORIGINAL_ENTITY_DATA, true);
        $this->spyArray('entityChangeSets', FunctionnalLogger::ENTITY_CHANGESET, false);
        $this->spyArray('entityStates', FunctionnalLogger::ENTITY_STATE, false);
        $this->spyArray('orphanRemovals', FunctionnalLogger::ENTITY_ORPHAN_REMOVAL, false);
        $this->spyArray('extraUpdates', FunctionnalLogger::EXTRA_UPDATE, false);
        $this->spyArray('entityInsertions', FunctionnalLogger::ENTITY_INSERT, false);
        $this->spyArray('entityUpdates', FunctionnalLogger::ENTITY_UPDATE, false);
        $this->spyArray('entityDeletions', FunctionnalLogger::ENTITY_DELETE, false);
        $this->spyArray('collectionUpdates', FunctionnalLogger::COLLECTION_UPDATED, false);
    }
}