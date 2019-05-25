<?php


namespace App\Demo;


use App\Entity\Article;
use App\Entity\Picture;

class CascadePersistWithOrphanRemovalScenario extends AbstractWorkflowDemo
{
    private $articeId;

    public function run()
    {
        $this->init();

        $this->stepNewArticleWithPicture();
        $this->stepNewPictureOnPreviousArticleExplicitPersist();
        $this->stepNewPictureOnPreviousArticleNotExplicitPersist();
    }

    public function stepNewArticleWithPicture()
    {
        $this->preStep(__METHOD__);


        $picture = new Picture();
        $picture->setUrl('firsturl');

        $article = new Article();
        $article->setTitle('first');
        $article->setPicture($picture);

        $this->em->persist($article);
        $this->em->flush();

        $this->articeId = $article->getId();
    }

    public function stepNewPictureOnPreviousArticleExplicitPersist()
    {
        $this->preStep(__METHOD__);

        /** @var Article $article */
        $article = $this->em->getRepository(Article::class)->find($this->articeId);

        $picture = new Picture();
        $picture->setUrl('newurl');

        $article->setPicture($picture);

        $this->em->persist($article);

        $this->em->flush();
    }

    public function stepNewPictureOnPreviousArticleNotExplicitPersist()
    {
        $this->preStep(__METHOD__);

        /** @var Article $article */
        $article = $this->em->getRepository(Article::class)->find($this->articeId);

        $picture = new Picture();
        $picture->setUrl('newurl');

        $article->setPicture($picture);

        $this->em->flush();
    }
}