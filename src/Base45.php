<?php

namespace SomeCatCode;

/**
 * Class Base45
 * 
 * @package SomeCatCode\Base45
 * @author Felix Kurth <it@wasmitleder.de>
 */
class Base45 {

    const CHARSET = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ $%*+-./:";

    /**
     * Returns the Base45 representation of a string
     * 
     * @param string $string
     * @return string
     */
    public static function encode($string) {
        $buffer = [];
        foreach (str_split($string, 2) as $x) {
            $buffer[] = (strlen($x) == 2) ? (ord($x[0]) << 8) + ord($x[1]) : ord($x[0]);
        }

        $response = '';
        foreach ($buffer as $x) {
            $tmp = '';
            do {
                $tmp .= self::CHARSET[$x % 45];
                $x = floor($x / 45);
            } while ($x > 45);
            $tmp .= self::CHARSET[$x];
            $response .= $tmp;
        }
        return $response;
    }

    /**
     * Decodes an Base45 String
     * 
     * @param string $base45
     * @return string
     * @throws \Exception
     */
    public static function decode($base45) {
        $buffer = [];
        foreach (str_split($base45) as $x) {
            $pos = strpos(self::CHARSET, $x);
            if ($pos === false) throw new \Exception("Invalid base45 character");
            $buffer[] = $pos;
        }

        $response = '';
        foreach (array_chunk($buffer, 3) as $chunk) {
            $x = array_sum(array_map(function ($v, $i) {
                return $v * pow(45, $i);
            }, $chunk, array_keys($chunk)));

            if ($x > 256) {
                $q = str_pad(decbin($x), 16, "0", STR_PAD_LEFT);
                $response .= chr(bindec(substr($q, 0, 8))) . chr(bindec(substr($q, -8, 8)));
            } else {
                $response .= chr($x);
            }
        }
        return $response;
    }
}
