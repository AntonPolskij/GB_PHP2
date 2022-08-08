<?php

spl_autoload_register(function ($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = str_replace('_', DIRECTORY_SEPARATOR, $file);
    $file = str_replace('GeekBrains\LevelTwo', './src', $file) . 'php';

    if (file_exists($file)) {
        require $file;
    }
});

