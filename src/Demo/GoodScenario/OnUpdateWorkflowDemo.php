<?php

namespace App\Demo\GoodScenario;

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
        $this->stepUseSetNewValueOnPreUpdateEvent();
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

    private function stepUseSetNewValueOnPreUpdateEvent()
    {
        $this->preStep(__METHOD__);

        $this->em->getEventManager()->addEventListener(Events::preUpdate, new Class()
        {

            public function preUpdate(PreUpdateEventArgs $eventArgs)
            {
                $eventArgs->setNewValue('name', 'product_updated_on_preupdate_event');
            }
        });

        $this->declanchOnUpdate();
    }
}