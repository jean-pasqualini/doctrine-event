<?php

namespace App\Demo\GoodScenario;

use App\Demo\AbstractWorkflowDemo;
use App\Entity\Product;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class OnPreflushWorkflowDemo extends AbstractWorkflowDemo
{
    public function run()
    {
        $this->init();
        $this->stepCreateFixture();
        $this->stepCreateProductWihtPersistOnPreFlushEvent();
    }

    private function stepCreateFixture()
    {
        $this->preStep(__METHOD__);

        $product = new Product();
        $product->setName('chocapic');
        $product->setLabel(__CLASS__);

        $this->em->persist($product);
        $this->em->flush();
    }

    private function declanchOnPreflush()
    {
        /** @var Product $product */
        $product = $this->em->getRepository(Product::class)->findOneBy(['label' => __CLASS__]);
        $product->setName(strrev($product->getName()));

        $this->em->flush();
    }

    private function stepCreateProductWihtPersistOnPreFlushEvent()
    {
        $this->preStep(__METHOD__);

        $this->em->getEventManager()->addEventListener(Events::preFlush, new Class()
        {
            public function preFlush(PreFlushEventArgs $eventArgs)
            {
                $em = $eventArgs->getEntityManager();

                $product = new Product();
                $product->setName('created onPreflush');

                $em->persist($product);
            }
        });

        $this->declanchOnPreflush();
    }
}