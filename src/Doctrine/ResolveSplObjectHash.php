<?php


namespace App\Doctrine;


use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Picture;
use App\Entity\Product;
use Doctrine\ORM\UnitOfWork;

class ResolveSplObjectHash
{
    /** @var UnitOfWork */
    private $unitOfWork;

    public function __construct(UnitOfWork $unitOfWork)
    {
        $this->unitOfWork = $unitOfWork;
    }

    public function resolve($index)
    {
        return $this->unitOfWork->findByHash($index);
    }
}