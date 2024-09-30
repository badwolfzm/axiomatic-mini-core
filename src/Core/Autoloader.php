<?php

namespace Core;

class Autoloader {
    public static function register() {
        spl_autoload_register([self::class, 'autoload']);
    }

    private static function autoload($class) {
        $prefix = 'src/';
        $file = $prefix . str_replace('\\', '/', $class) . '.php';

        if (file_exists($file)) {
            require $file;
        }
    }
}
