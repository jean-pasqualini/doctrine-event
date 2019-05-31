<?php

namespace App;

class Logger
{
    const MODE_1 = 1;
    const MODE_2 = 2;

    const FORMAT = [
        self::MODE_1 => '%s [%s] : %s',
        self::MODE_2 => "\033[1m\033[47m--> %s \e[0m"
    ];

    public function log($type, $message, array $context = [], $mode = self::MODE_1)
    {
        if (self::MODE_2 === $mode) {
            $params = array_keys($context);
            $values = array_values($context);

            $message = str_replace($params, $values, $message);

            echo sprintf(
                    self::FORMAT[$mode],
                    $message
                ).PHP_EOL;
            return;
        }

        $params = array_keys($context);
        $values = array_values($context);
        if (self::MODE_1 === $mode) {
            foreach ($values as &$value) {
                $value = "\033[0;36m".$value."\033[0m";
            }
        }

        $message = str_replace($params, $values, $message);

        echo sprintf(
                self::FORMAT[$mode],
                date('H:i:s'),
                $type,
                $message
            ).PHP_EOL;
    }
}