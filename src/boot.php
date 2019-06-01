<?php

namespace App;

use App\Doctrine\FunctionnalLogger;
use App\EventListener\DoctrineEventListener;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\Tools\Setup;

$config = Setup::createAnnotationMetadataConfiguration(
    [__DIR__.'/Entity'],
    true,
    null,
    null,
    false
);

$logger = new Logger();
$config->setSQLLogger(new SqlLogger($logger));

$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/../database/db.sqlite',
);

$eventManager = new EventManager();
$eventManager->addEventSubscriber(new DoctrineEventListener($logger));

$entityManager = new EntityManagerLoggerProxy($logger, EntityManager::create($conn, $config, $eventManager));
$eventManager->addEventListener(Events::onClear, new Class() {
    public function onClear() {
        FunctionnalLogger::onClear();
    }
});

return $entityManager;