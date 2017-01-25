# ls-artnet-php
## Art-Net Broadcast Client for PHP

### Art-Net ArtDmx Client example

This project provides an ArtDmx Client written in PHP that will set DMX values to an ArtNet node
using UDP Broadcast.

```php
<?php
// ...

use LarkSpark\ArtNet\ArtDmxClient;

// create new artdmx client 
$client = new ArtDmxClient('10.10.14.255', $port = 6454, $universe = 0);

// broadcast our DMX values
$client->broadcast([0, 0, 0, 255, 0, 128]);

```

### Written by
* D. Dante Lorenso <dante@lorenso.com>
