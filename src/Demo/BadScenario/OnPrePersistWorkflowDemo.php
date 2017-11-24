<?php

namespace App\Demo\BadScenario;

use App\Demo\AbstractWorkflowDemo;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class OnPrePersistWorkflowDemo extends AbstractWorkflowDemo
{
    public function run()
    {
        $this->init();
        $this->stepRemoveRelationOnPrepPersist();
    }

    public function stepRemoveRelationOnPrepPersist()
    {
        $this->preStep(__METHOD__);

        $this->em->getEventManager()->addEventListener(Events::prePersist, new Class()
        {
            public function prePersist(LifecycleEventArgs $eventArgs)
            {
                $entity = $eventArgs->getEntity();
                if ($entity instanceof Category) {
                    $products = $entity->getProducts();
                    /** @var Product $product */
                    foreach ($products as $product) {
                        $entity->removeProduct($product);
                    }
                }
            }
        });

        $product = new Product();
        $product->setName('chocapic');
        $product->setLabel(__CLASS__);

        $category = new Category();
        $category->setName('cereale');
        $category->addProduct($product);

        $this->em->persist($product);
        $this->em->persist($category);
        $this->em->flush();
    }
}