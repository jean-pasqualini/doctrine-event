<?php

namespace App\Demo\GoodScenario;

use App\Demo\AbstractWorkflowDemo;
use App\Entity\Category;
use App\Entity\Product;

class CategoryWithProductWorkflowDemo extends AbstractWorkflowDemo
{
    public function run()
    {
        $this->init();
        $this->stepCreateCategoryWithOneProductOnManualPersistProduct();
        $this->stepCreateCategoryWithOneProductOnAutoPersistProduct();
    }

    public function stepCreateCategoryWithOneProductOnManualPersistProduct()
    {
        $this->preStep(__METHOD__);

        $product = new Product();
        $product->setName('Samsung galaxy S7');
        $this->em->persist($product);

        $category = new Category();
        $category->setName('smartphone');
        $category->addProduct($product);
        $this->em->persist($category);

        $this->em->flush();
    }

    public function stepCreateCategoryWithOneProductOnAutoPersistProduct()
    {
        $this->preStep(__METHOD__);

        $product = new Product();
        $product->setName('LG TV');

        $category = new Category();
        $category->setName('Television');
        $category->addProduct($product);
        $this->em->persist($category);

        $this->em->flush();
    }
}