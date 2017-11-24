<?php

namespace App\Demo;

use Doctrine\ORM\EntityManager;

abstract class AbstractWorkflowDemo
{
    /** @var EntityManager */
    protected $em;

    public function init()
    {
        $this->em = require __DIR__.'/../boot.php';
    }

    abstract public function run();

    protected function preStep($method)
    {
        static $countStep = 1;
        $title = $countStep.'. '.$method;
        $countStep++;

        echo PHP_EOL;
        echo $title.PHP_EOL;
        echo str_repeat('-', strlen($title)).PHP_EOL;
    }
}