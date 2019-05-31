<?php


namespace App\Doctrine;


use App\Logger;
use Doctrine\ORM\UnitOfWork;

trait UnitOfWorkTrait
{

    public function wrapArray()
    {
        $resoverSplObjectHash = new ResolveSplObjectHash($this);
        $logger = new Logger();

        if (UnitOfWork::WRAP_ARRAY[FunctionnalLogger::IDENTIY_MAP_CLASS]) {
            $this->identityMap = new IdentityMap(new FunctionnalLogger($logger, FunctionnalLogger::IDENTIY_MAP_CLASS, $resoverSplObjectHash), $this->identityMap);
        }

        if (UnitOfWork::WRAP_ARRAY[FunctionnalLogger::ENTITY_IDENTIFIER] && !$this->entityIdentifiers instanceof LoggedMap) {
            $this->entityIdentifiers = new LoggedMap(
                new FunctionnalLogger($logger, FunctionnalLogger::ENTITY_IDENTIFIER, $resoverSplObjectHash),
                $this->entityIdentifiers,
                true,
                false
            );
        }

        if (UnitOfWork::WRAP_ARRAY[FunctionnalLogger::ORIGINAL_ENTITY_DATA] && !$this->originalEntityData instanceof LoggedMap) {
            $this->originalEntityData = new LoggedMap(
                new FunctionnalLogger($logger, FunctionnalLogger::ORIGINAL_ENTITY_DATA, $resoverSplObjectHash),
                $this->originalEntityData,
                true,
                true
            );
        }

        if (UnitOfWork::WRAP_ARRAY[FunctionnalLogger::ENTITY_CHANGESET]) {
            $this->entityChangeSets = new LoggedMap(
                new FunctionnalLogger($logger, FunctionnalLogger::ENTITY_CHANGESET, $resoverSplObjectHash),
                $this->entityChangeSets,
                true,
                false,
                $resoverSplObjectHash
            );
        }

        if (UnitOfWork::WRAP_ARRAY[FunctionnalLogger::ENTITY_STATE] && !$this->entityStates instanceof LoggedMap) {
            $this->entityStates = new LoggedMap(
                new FunctionnalLogger($logger, FunctionnalLogger::ENTITY_STATE, $resoverSplObjectHash),
                $this->entityStates,
                true,
                false,
                $resoverSplObjectHash
            );
        }

        if (UnitOfWork::WRAP_ARRAY[FunctionnalLogger::EXTRA_UPDATE]) {
            $this->extraUpdates = new LoggedMap(
                new FunctionnalLogger($logger, FunctionnalLogger::EXTRA_UPDATE, $resoverSplObjectHash),
                $this->extraUpdates,
                true,
                false,
                $resoverSplObjectHash
            );
        }

        if (UnitOfWork::WRAP_ARRAY[FunctionnalLogger::ENTITY_INSERT]) {
            $this->entityInsertions = new LoggedMap(new FunctionnalLogger($logger, FunctionnalLogger::ENTITY_INSERT, $resoverSplObjectHash), $this->entityInsertions, true, false);
        }

        if (UnitOfWork::WRAP_ARRAY[FunctionnalLogger::ENTITY_UPDATE]) {
            $this->entityUpdates = new LoggedMap(new FunctionnalLogger($logger, FunctionnalLogger::ENTITY_UPDATE, $resoverSplObjectHash), $this->entityUpdates, true, false);
        }

        if (UnitOfWork::WRAP_ARRAY[FunctionnalLogger::ENTITY_DELETE]) {
            $this->entityDeletions = new LoggedMap(new FunctionnalLogger($logger, FunctionnalLogger::ENTITY_DELETE, $resoverSplObjectHash), $this->entityDeletions, true, false);
        }
        //$this->entityInsertions = new \ArrayObject($this->entityInsertions);
        //$this->entityUpdates = new \ArrayObject($this->entityUpdates);
        //$this->entityDeletions = new \ArrayObject($this->entityDeletions);
    }
}