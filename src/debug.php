<?php

register_tick_function('show_instruction', true);

function show_instruction() {
    $backtrace = debug_backtrace();
    $line = $backtrace[0]['line'] - 1;
    $file = $backtrace[0]['file'];

    if ($file == __FILE__) return;

    //echo 'method : '.json_encode(array_keys($backtrace[0]), true).PHP_EOL;
    $stack = array_column($backtrace, 'function');

    echo '+ PHP :::: '.implode(' => ', array_reverse($stack)).PHP_EOL;
}