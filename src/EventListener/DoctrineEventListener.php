<?php

namespace App\EventListener;

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

    public function __call($name, $arguments)
    {
        $class = 'all';
        if (isset($arguments[0]) && is_object($arguments[0])) {
            if (method_exists($arguments[0], 'getEntity')) {
                $class = get_class($arguments[0]->getEntity());
            }
        }

        $this->logger->log(
            'INFO.event',
            'event {name} on {class}',
            [
                '{name}' => $name,
                '{class}' => $class,
            ]
        );
    }
}