<?php

use LogicalSteps\Async\Async;
use LogicalSteps\Async\Commands;
use LogicalSteps\Async\ConsoleLogger;

require __DIR__ . '/../vendor/autoload.php';


function blow()
{
    yield;
    try {
        yield Commands::throw(Exception::class) => blast();
    } catch (Exception $e) {
        throw new Exception('blow', 0, $e);
    }
}

function blast()
{
    yield;
    throw new Exception('blast');
}

function run()
{
    yield 1;
    try {
        yield Commands::throw(Exception::class) => blow();
    } catch (Exception $e) {
        echo 'catch ' . get_class($e) . ': ' . $e->getMessage() . PHP_EOL;
    }
    return 'run completed';
}

//Async::setLogger(new ConsoleLogger);

Async::await(run())->then('var_dump', function (Throwable $t) {
    echo get_class($t) . ': ' . $t->getMessage() . PHP_EOL;
});
