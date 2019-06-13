<?php


namespace App\Demo;


use App\Entity\Article;
use App\Entity\ReadOnlyEntity;

class ReadOnlyScenario extends AbstractWorkflowDemo
{
    private $readOnlyEntity;

    public function run()
    {
        $this->init();
        $this->stepCreateReadOnlyObject();
        $this->stepUpdateReadOnlyObject();
        $this->stepDeleteReadOnlyObject();
        $this->stepUpdateMarkedReadOnlyObject();
    }

    public function stepCreateReadOnlyObject()
    {
        $this->preStep(__METHOD__);

        $entity = new ReadOnlyEntity();
        $entity->setTitle('created');

        $this->em->persist($entity);
        $this->em->flush();

        $this->readOnlyEntity = $entity;
    }

    public function stepUpdateReadOnlyObject()
    {
        $this->preStep(__METHOD__);

        $this->readOnlyEntity->setTitle('updated');

        $this->em->persist($this->readOnlyEntity);
        $this->em->flush();
    }

    public function stepUpdateMarkedReadOnlyObject()
    {
        $this->preStep(__METHOD__);

        $article = new Article();
        $article->setTitle('created');

        $this->em->persist($article);
        $this->em->flush();

        $this->em->getUnitOfWork()->markReadOnly($article);

        $article->setTitle('updated');

        $this->em->persist($article);
        $this->em->flush();
    }

    public function stepDeleteReadOnlyObject()
    {
        $this->preStep(__METHOD__);

        $this->em->remove($this->readOnlyEntity);
        $this->em->flush();
    }
}