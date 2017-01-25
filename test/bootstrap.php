<?php
// class auto-loader
spl_autoload_register(
    function ($class_name) {
        // PSR-4: convert class name to file name
        $lib_file = '../library/' . strtr(ltrim($class_name, '\\'), ['\\' => '/']) . '.php';
        if (file_exists($lib_file)) {
            include $lib_file;
            return true;
        }

        // not found!
        return false;
    }
);
