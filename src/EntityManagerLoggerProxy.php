<?php

namespace App;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

class EntityManagerLoggerProxy extends ClassLoggerProxy implements EntityManagerInterface
{
    public function getCache()
    {
        return parent::getCache();
    }

    public function getConnection()
    {
        return parent::getConnection();
    }

    public function getExpressionBuilder()
    {
        return parent::getExpressionBuilder();
    }

    public function beginTransaction()
    {
        return parent::beginTransaction();
    }

    public function transactional($func)
    {
        return parent::transactional();
    }

    public function commit()
    {
        return parent::commit();
    }

    public function rollback()
    {
        return parent::rollback();
    }

    public function createQuery($dql = '')
    {
        return parent::createQuery($dql);
    }

    public function createNamedQuery($name)
    {
        return parent::createNamedQuery($name);
    }

    public function createNativeQuery($sql, ResultSetMapping $rsm)
    {
        return parent::createNativeQuery($sql, $rsm);
    }

    public function createNamedNativeQuery($name)
    {
        return parent::createNamedNativeQuery($name);
    }

    public function createQueryBuilder()
    {
        return parent::createQueryBuilder();
    }

    public function getReference($entityName, $id)
    {
        return parent::getReference($entityName, $id);
    }

    public function getPartialReference($entityName, $identifier)
    {
        return parent::getPartialReference($entityName, $identifier);
    }

    public function close()
    {
        return parent::close();
    }

    public function copy($entity, $deep = false)
    {
        return parent::copy($entity, $deep);
    }

    public function lock($entity, $lockMode, $lockVersion = null)
    {
        return parent::lock($entity, $lockMode, $lockVersion);
    }

    public function getEventManager()
    {
        return parent::getEventManager();
    }

    public function getConfiguration()
    {
        return parent::getConfiguration();
    }

    public function isOpen()
    {
        return parent::isOpen();
    }

    public function getUnitOfWork()
    {
        return parent::getUnitOfWork();
    }

    public function getHydrator($hydrationMode)
    {
        return parent::getHydrator($hydrationMode);
    }

    public function newHydrator($hydrationMode)
    {
        return parent::newHydrator($hydrationMode);
    }

    public function getProxyFactory()
    {
        return parent::getProxyFactory();
    }

    public function getFilters()
    {
        return parent::getFilters();
    }

    public function isFiltersStateClean()
    {
        return parent::isFiltersStateClean();
    }

    public function hasFilters()
    {
        return parent::hasFilters();
    }

    public function find($className, $id)
    {
        return parent::find($className, $id);
    }

    public function persist($object)
    {
        return parent::persist($object);
    }

    public function remove($object)
    {
        return parent::remove($object);
    }

    public function merge($object)
    {
        return parent::merge($object);
    }

    public function clear($objectName = null)
    {
        return parent::clear($objectName);
    }

    public function detach($object)
    {
        return parent::detach($object);
    }

    public function refresh($object)
    {
        return parent::refresh($object);
    }

    public function flush()
    {
        return parent::flush();
    }

    public function getRepository($className)
    {
        return parent::getRepository($className);
    }

    public function getClassMetadata($className)
    {
        return parent::getClassMetadata($className);
    }

    public function getMetadataFactory()
    {
        return parent::getMetadataFactory();
    }

    public function initializeObject($obj)
    {
        return parent::initializeObject($obj);
    }

    public function contains($object)
    {
        return parent::contains($object);
    }
}