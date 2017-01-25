<?php
/**
 * Copyright (c) 2017 D. Dante Lorenso <dante@lorenso.com>.  All Rights Reserved.
 * This source file is subject to the MIT license that is bundled with this package
 * in the file LICENSE.txt.  It is also available at: https://opensource.org/licenses/MIT
 */
namespace LarkSpark\ArtNet;

use Exception;
use LarkSpark\Debug;

/**
 * Class ArtDmxClient
 *
 * @package LarkSpark\ArtNet
 */
class ArtDmxClient
{
    const MIN_LENGTH = 64;

    /**
     * @var string
     */
    private $host = '2.255.255.255';

    /**
     * @var int
     */
    private $port = 6454;

    /**
     * @var int
     */
    private $universe = 0;

    /**
     * ArtNetClient constructor.
     *
     * @param string|null $host
     * @param int|null $port
     * @param int|null $universe
     */
    public function __construct(string $host = null, int $port = null, int $universe = null)
    {
        if (!is_null($host)) {
            $this->host = $host;
        }

        if (!is_null($port)) {
            $this->port = $port;
        }

        if (!is_null($universe)) {
            $this->universe = $universe;
        }
    }

    /**
     * @return string
     */
    private function artDmxHeader()
    {
        $header = "Art-Net\x00"; // 0-7 ID (8 bytes)
        $header .= "\x00\x50"; // 8-9 Opcode = 0x5000 (ArtDmx data packet). Zero start code DMX512 info for single Universe.
        $header .= "\x00\x0e"; // 10-11 ProtVer (Hi/Lo) = "14"
        return $header;
    }

    /**
     * @param array $dmx
     *
     * @return string
     */
    private function artDmxPayload(array $dmx = [])
    {
        // init empty payload
        $len = max(self::MIN_LENGTH, count($dmx));
        $payload = str_repeat(chr(0), $len);

        // apply dmx values to payload
        foreach ($dmx as $x => $value) {
            $payload[$x] = chr($value);
        }

        // calculate low and high byte for DMX bytes
        $len_hi = floor(strlen($payload) / 256);
        $len_lo = strlen($payload) % 256;

        // generate ARTDMX packet
        $artdmx = chr(0); // 12 Sequence = 0 (disabled)
        $artdmx .= chr(0); // 13 Physical = 0 (informational only, use universe for routing)
        $artdmx .= "\x00\x00"; // 14-15 SubUni / Net // TODO: use $this->universe here
        $artdmx .= chr($len_hi) . chr($len_lo); // 16-17 Length Hi/Lo (length of DMX512 data, even number 2-512, # channels in packet)

        // return payload packet
        return $artdmx . $payload;
    }

    /**
     * @param array $dmx
     *
     * @return bool
     * @throws \Exception
     */
    public function broadcast(array $dmx = [])
    {
        // generate packet
        $packet = $this->artDmxHeader() . $this->artDmxPayload($dmx);
        Debug::hexDump($packet);

        // broadcast the packet of dmx data via art-net broadcast
        $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if (!$sock) {
            throw new Exception('socket create failed: ' . socket_strerror(socket_last_error($sock)));
        }

        // socket options
        socket_set_nonblock($sock);
        socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, true);
        socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, true);
        socket_set_option($sock, SOL_SOCKET, SO_REUSEPORT, true);

        // bind to any address & our port.
        if (!socket_bind($sock, '0.0.0.0', $this->port)) {
            throw new Exception('socket bind failed: ' . socket_strerror(socket_last_error($sock)));
        }

        // broadcast data
        $result = socket_sendto($sock, $packet, strlen($packet), 0, $this->host, $this->port);
        if (!$result) {
            throw new Exception('socket broadcast failed: ' . socket_strerror(socket_last_error($sock)));
        }

        // close socket and be done
        socket_close($sock);
        return true;
    }
}