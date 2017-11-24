<?php

namespace App\Demo\BadScenario;

use App\Demo\AbstractWorkflowDemo;
use App\Entity\Product;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class OnUpdateWorkflowDemo extends AbstractWorkflowDemo
{
    public function run()
    {
        $this->init();
        $this->stepCreateFixture();
        $this->stepCreateProductWithPersistAndFlushOnPreUpdateEvent();
        $this->stepCreateProductWithPersistOnlyOnPreUpdateEvent();
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

    private function declanchOnUpdate()
    {
        /** @var Product $product */
        $product = $this->em->getRepository(Product::class)->findOneBy(['label' => __CLASS__]);
        $product->setName(strrev($product->getName()));

        $this->em->flush();
    }

    private function stepCreateProductWithPersistAndFlushOnPreUpdateEvent()
    {
        $this->preStep(__METHOD__);

        $this->em->getEventManager()->addEventListener(Events::preUpdate, new Class() {

            private $passed = false;

            public function preUpdate(PreUpdateEventArgs $eventArgs)
            {
                if ($this->passed) {
                    return;
                }
                $this->passed = true;

                $em = $eventArgs->getEntityManager();

                $product = new Product();
                $product->setName('product_create_on_preupdate_event');

                $em->persist($product);
                $em->flush();
            }
        });

        $this->declanchOnUpdate();
    }

    private function stepCreateProductWithPersistOnlyOnPreUpdateEvent()
    {
        $this->preStep(__METHOD__);

        $this->em->getEventManager()->addEventListener(Events::preUpdate, new Class() {

            public function preUpdate(PreUpdateEventArgs $eventArgs)
            {
                $this->passed = true;

                $em = $eventArgs->getEntityManager();

                $product = new Product();
                $product->setName('product_create_on_preupdate_event');

                $em->persist($product);
            }
        });

        $this->declanchOnUpdate();
    }
}