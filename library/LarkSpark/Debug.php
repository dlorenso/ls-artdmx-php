<?php
namespace LarkSpark;

/**
 * Class Debug
 *
 * @package LarkSpark
 */
class Debug
{
    /**
     * @param $data
     * @param string $newline
     * @param int $width - number of bytes per line
     * @param string $pad - padding for non-visible characters
     */
    public static function hexDump($data, string $newline = "\n", $width = 16, $pad = '.')
    {
        static $from = '';
        static $to = '';

        if ($from === '') {
            for ($i = 0; $i <= 0xFF; $i++) {
                $from .= chr($i);
                $to .= ($i >= 0x20 && $i <= 0x7E) ? chr($i) : $pad;
            }
        }

        $hex = str_split(bin2hex($data), $width * 2);
        $chars = str_split(strtr($data, $from, $to), $width);

        $offset = 0;
        foreach ($hex as $i => $line) {
            echo sprintf('%6X', $offset) . ' : ' . implode(' ', str_split($line, 2)) . ' [' . $chars[$i] . ']' . $newline;
            $offset += $width;
        }
    }
}