<?php


namespace App\Demo;


use App\Entity\Article;
use App\Entity\Picture;
use App\Logger;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class CascadePersistWithOrphanRemovalScenario extends AbstractWorkflowDemo
{
    private $articeId;

    public function run()
    {
        $this->init();

        $this->stepNewArticleWithPicture();
        $this->stepNewPictureOnPreviousArticleExplicitPersist();
        $this->stepNewPictureOnPreviousArticleNotExplicitPersist();
        $this->stepTestPersistWhenPrepersistDeclanchedOnFlush();
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

    public function stepTestPersistWhenPrepersistDeclanchedOnFlush()
    {
        $this->preStep(__METHOD__);

        $this->em->getEventManager()->addEventListener(Events::prePersist, new Class()
        {

            public function prePersist(LifecycleEventArgs $eventArgs)
            {
                if (!$eventArgs->getEntity() instanceof Picture) {
                    return;
                }

                $newArticle = new Article();
                $newArticle->setTitle('persist on prePersist');

                $eventArgs->getEntityManager()->persist($newArticle);
            }
        });

        /** @var Article $article */
        $article = $this->em->getRepository(Article::class)->find($this->articeId);

        $picture = new Picture();
        $picture->setUrl('newurl');

        $article->setPicture($picture);

        try {
            $this->em->flush();
        } catch (NotNullConstraintViolationException $exception) {
            $logger = new Logger();
            $logger->log('EXCEPTION.doctrine', str_replace(PHP_EOL, ' ', $exception->getMessage()));
        }
    }
}