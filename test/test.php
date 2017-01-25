<?php
/**
 * Copyright (c) 2017 D. Dante Lorenso <dante@lorenso.com>.  All Rights Reserved.
 * This source file is subject to the MIT license that is bundled with this package
 * in the file LICENSE.txt.  It is also available at: https://opensource.org/licenses/MIT
 */

require 'bootstrap.php';

use LarkSpark\ArtNet\ArtDmxClient;

// read test input from command line
$argv = $_SERVER['argv']??[];
array_shift($argv); // script

// map our inputs to fixture names
$dmx = [
    'bedroom' => [
        'red' => 0,
        'green' => 0,
        'blue' => 0
    ],
    'livingroom' => [
        'red' => array_shift($argv),
        'green' => array_shift($argv),
        'blue' => array_shift($argv)
    ]
];

// create new artdmx client
$client = new ArtDmxClient('10.10.14.255', 6454);

// broadcast our DMX values
$client->broadcast(
    [
        $dmx['bedroom']['green'],
        $dmx['bedroom']['red'],
        $dmx['bedroom']['blue'],
        $dmx['livingroom']['green'],
        $dmx['livingroom']['red'],
        $dmx['livingroom']['blue']
    ]
);
