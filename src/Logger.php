<?php

namespace App;

class Logger
{
    const MODE_1 = 1;
    const MODE_2 = 2;

    const COLOR_BLACK = "\033[40;40m \033[0m";
    const COLOR_RED = "\033[40;41m \033[0m";
    const COLOR_GREEN = "\033[40;42m \033[0m";
    const COLOR_YELLOW = "\033[40;43m \033[0m";
    const COLOR_BLUE = "\033[40;44m \033[0m";
    const COLOR_VIOLET = "\033[40;45m \033[0m";
    const COLOR_BLUE_LIGHT = "\033[40;46m \033[0m";
    const COLOR_WHITE = "\033[40;47m \033[0m";

    public function log($type, $message, array $context = [], $color = self::COLOR_BLACK)
    {
        $params = array_keys($context);
        $values = array_values($context);
            foreach ($values as &$value) {
                $value = "\033[0;36m".$value."\033[0m";
            }

        $message = str_replace($params, $values, $message);

        echo sprintf(
                "%s %s [%s] : %s",
                $color,
                date('H:i:s'),
                $type,
                $message
            ).PHP_EOL;
    }
}