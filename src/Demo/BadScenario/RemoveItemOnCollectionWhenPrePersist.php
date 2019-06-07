<?php


namespace App\Demo\BadScenario;

use App\Demo\AbstractWorkflowDemo;
use App\Entity\Article;
use App\Entity\Commentaire;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

// Est-ce vraiment un mauvais scénario, selon la doc oui mais je suis en train de chercher si oui et pourquoi ?
// En cours d'investigation
class RemoveItemOnCollectionWhenPrePersist extends AbstractWorkflowDemo
{
    /** @var Article */
    private $article;

    public function run()
    {
        $this->init();

        $this->stepCreateArticleWithOneComment();
        $this->stepUpdateArticleAndRemoveOneCommentFromCollection();
    }

    public function stepCreateArticleWithOneComment()
    {
        $this->preStep(__METHOD__);

        $article = new Article();
        $article->setTitle('create');

        $comment = new Commentaire();
        $comment->setComment('create');

        $article->addComment($comment);

        $this->em->persist($article);
        $this->em->flush();

        $this->article = $article;
    }

    public function stepUpdateArticleAndRemoveOneCommentFromCollection()
    {
        $this->preStep(__METHOD__);

        $this->em->getEventManager()->addEventListener(Events::prePersist, new Class($this->article)
        {
            /** @var Article */
            private $article;

            public function __construct(Article $article)
            {
                $this->article = $article;
            }

            public function prePersist(LifecycleEventArgs $eventArgs)
            {
                if (!$eventArgs->getEntity() instanceof Article) {
                    return;
                }

                $this->article->getComments()->removeElement(
                    $this->article->getComments()->first()
                );
            }
        });

        $newArticle = new Article();
        $newArticle->setTitle('new article');

        $this->em->persist($newArticle);
        $this->em->flush();
    }
}