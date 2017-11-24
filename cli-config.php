<?php

namespace App;

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require __DIR__.'/vendor/autoload.php';

$entityManager = require __DIR__.'/src/boot.php';

return ConsoleRunner::createHelperSet($entityManager);