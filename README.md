# ls-artnet-php
## Art-Net Broadcast Client for PHP

### Client example

Here is an example that sends 4 dmx channels 10 times to each node it finds on the network, and then exits.

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
