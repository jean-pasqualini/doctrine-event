<?php
namespace App;

use App\Demo\GoodScenario;
use App\Demo\BadScenario;
use Symfony\Component\Process\Process;

require __DIR__.'/../vendor/autoload.php';

$demos = [
    new GoodScenario\ProductWorkflowDemo(),
    new GoodScenario\CategoryWithProductWorkflowDemo(),
    new GoodScenario\OnUpdateWorkflowDemo(),
    new GoodScenario\OnPreflushWorkflowDemo(),
    new GoodScenario\OnPrePersistWorkflowDemo(),
    new GoodScenario\OnPostPersistWorkflowDemo(),
    new BadScenario\OnUpdateWorkflowDemo(),
    new BadScenario\OnPrePersistWorkflowDemo(),
];

$filter = $argv[1] ?? null;

foreach($demos as $demo) {
    $class = get_class($demo);

    if (!$filter || strpos($class, $filter) !== false) {

        // Restore database between workflow
        $process = new Process('make db-reset', __DIR__.'/..');
        $process->run();

        echo PHP_EOL;
        echo '======'.str_repeat('=', strlen($class)).'====='.PHP_EOL;
        echo "|     \033[41;97m".get_class($demo)."\033[0m    |".PHP_EOL;
        echo '======'.str_repeat('=', strlen($class)).'====='.PHP_EOL;

        $demo->run();
    }
}