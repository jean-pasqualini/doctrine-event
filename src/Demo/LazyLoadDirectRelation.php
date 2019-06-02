<?php


namespace App\Demo;


use App\Entity\Article;
use App\Entity\Picture;
use App\Logger;

class LazyLoadDirectRelation extends AbstractWorkflowDemo
{
    private $id;
    private $oid;
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    public function run()
    {
        $this->init();

        $this->stepCreateArticleWithPicture();
        $this->stepLazyLoadPicture();
    }

    public function stepCreateArticleWithPicture()
    {
        $this->preStep(__METHOD__);

        $article = new Article();
        $article->setTitle('lazy');

        $picture = new Picture();
        $picture->setUrl('lazy_img');

        $article->setPicture($picture);

        $this->em->persist($article);
        $this->em->flush();

        $this->em->clear();

        $this->id = $article->getId();
        $this->oid = spl_object_hash($article);
    }

    public function stepLazyLoadPicture()
    {
        $this->preStep(__METHOD__);

        /** @var Article $article */
        $article = $this->em->getRepository(Article::class)->find($this->id);
        $articleByTitle = $this->em->getRepository(Article::class)->findOneBy(['title' => 'lazy']);

        $this->logger->log('INFO.oid', sprintf(
            'previous: %s, current: %s (equals ? %s)',
            $this->oid,
            spl_object_hash($article),
            ($this->oid == spl_object_hash($article)) ? 'yes' : 'no'
        ));

        $this->logger->log('INFO.oid', sprintf(
            'by id: %s, by title: %s (equals ? %s)',
            spl_object_hash($article),
            spl_object_hash($articleByTitle),
            (spl_object_hash($article) == spl_object_hash($articleByTitle)) ? 'yes' : 'no'
        ));

        $this->logger->log('INFO.debug', get_class($article));
        $this->logger->log('INFO.debug', get_class($article->getPicture()));
        $this->logger->log('INFO.debug', $article->getPicture()->getId());
        $this->logger->log('INFO.debug', $article->getPicture()->getUrl());



    }
}