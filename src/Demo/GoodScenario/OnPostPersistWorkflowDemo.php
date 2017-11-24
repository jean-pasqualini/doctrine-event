<?php

namespace App\Demo\GoodScenario;

use App\Demo\AbstractWorkflowDemo;
use App\Entity\Product;
use App\Logger;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class OnPostPersistWorkflowDemo extends AbstractWorkflowDemo
{
    public function run()
    {
        $this->init();
        $this->stepLogPrimaryKeyOnPrePersistEvent();
    }

    private function declanchOnPostPersist()
    {
        $product = new Product();
        $product->setName('chocapic');
        $product->setLabel(__CLASS__);

        $this->em->persist($product);
        $this->em->flush();
    }

    private function stepLogPrimaryKeyOnPrePersistEvent()
    {
        $this->preStep(__METHOD__);

        $this->em->getEventManager()->addEventListener(Events::postPersist, new Class(new Logger())
        {
            /** @var Logger */
            private $logger;

            public function __construct(Logger $logger)
            {
                $this->logger = $logger;
            }

            public function postPersist(LifecycleEventArgs $eventArgs)
            {
                $this->logger->log('INFO.demo', 'new {class} with primary key {id}', [
                    '{class}' => get_class($eventArgs->getEntity()),
                    '{id}' => $eventArgs->getEntity()->getId(),
                ]);
            }
        });

        $this->declanchOnPostPersist();
    }
}