<?php

if (!function_exists('dv')) {
    /**
     * Išveda į naršyklę kintamojo struktūrą
     * @param  mixed  ...$var
     */
    function dv(...$var)
    {
        foreach (func_get_args() as $var) {
            \Symfony\Component\VarDumper\VarDumper::dump($var);
        }
    }
}

if (!function_exists('dvd')) {
    /**
     * Išveda į naršyklę kintamojo struktūrą ir nutraukianti script'o vykdymą
     * @param  mixed  ...$var
     */
    function dvd(...$var)
    {
        foreach (func_get_args() as $var) {
            dv($var);
        }
        die();
    }
}
