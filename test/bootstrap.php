<?php
/**
 * Copyright (c) 2017 D. Dante Lorenso <dante@lorenso.com>.  All Rights Reserved.
 * This source file is subject to the MIT license that is bundled with this package
 * in the file LICENSE.txt.  It is also available at: https://opensource.org/licenses/MIT
 */

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
