<?php

namespace App\Demo\GoodScenario;

use App\Demo\AbstractWorkflowDemo;
use App\Entity\Product;

class ProductWorkflowDemo extends AbstractWorkflowDemo
{
    public function run()
    {
        $this->init();
        $this->stepCreateProduct();
        $this->stepfindAllProduct();
        $this->stepModifyAllProduct();
        $this->stepClear();
        $this->stepLoadAfterClear();
        $this->stepRemoveAllProduct();
    }

    private function stepCreateProduct()
    {
        $this->preStep(__METHOD__);

        $product = new Product();
        $product->setName('chocolat chaud');

        $this->em->persist($product);
        $this->em->flush();
    }

    private function stepfindAllProduct()
    {
        $this->preStep(__METHOD__);

        $productRepository = $this->em->getRepository(Product::class);
        $products = $productRepository->findBy([], null, 2);
    }

    private function stepModifyAllProduct()
    {
        $this->preStep(__METHOD__);

        $productRepository = $this->em->getRepository(Product::class);
        $products = $productRepository->findBy([], null, 2);

        /** @var Product $product */
        foreach ($products as $product) {
            $product->setName($product->getName().'-modified');
        }

        $this->em->flush();
    }

    private function stepClear()
    {
        $this->preStep(__METHOD__);
        $this->em->clear();
    }

    private function stepLoadAfterClear()
    {
        $this->preStep(__METHOD__);

        $productRepository = $this->em->getRepository(Product::class);
        $productRepository->findBy([], null, 2);
    }

    private function stepRemoveAllProduct()
    {
        $this->preStep(__METHOD__);

        $productRepository = $this->em->getRepository(Product::class);
        $products = $productRepository->findBy([], null, 2);

        foreach ($products as $product) {
            $this->em->remove($product);
        }

        $this->em->flush();
    }
}