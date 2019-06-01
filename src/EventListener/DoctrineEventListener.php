<?php

namespace App\EventListener;

use App\Doctrine\FunctionnalLogger;
use App\Logger;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;

class DoctrineEventListener implements EventSubscriber
{
    /** @var Logger */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::postPersist,
            Events::preFlush,
            Events::onFlush,
            Events::postFlush,
            Events::postLoad,
            Events::preRemove,
            Events::postRemove,
            Events::preUpdate,
            Events::postUpdate
        ];
    }

    public function dumpObject($scalar, $hash = null)
    {
        if (is_object($scalar)) {
            return get_class($scalar) . ' (id=' . ($scalar->getId() ?: 'null') . ', hash='.FunctionnalLogger::getLiteralHash(spl_object_hash($scalar)).')';
        }

        return 'null';
    }

    public function __call($name, $arguments)
    {
        $class = 'all';
        $color = Logger::COLOR_BLACK;
        if (isset($arguments[0]) && is_object($arguments[0])) {
            if (method_exists($arguments[0], 'getEntity')) {
                $class = $this->dumpObject($arguments[0]->getEntity());
                $color = FunctionnalLogger::getColorHash(spl_object_hash($arguments[0]->getEntity()));
            }
        }

        $this->logger->log(
            'INFO.event',
            'event {name} on {class}',
            [
                '{name}' => $name,
                '{class}' => $class,
            ],
            $color
        );
    }
}