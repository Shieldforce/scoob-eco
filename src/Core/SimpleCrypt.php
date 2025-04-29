<?php

namespace ScoobEco\Core;

use Exception;

class SimpleCrypt
{
    private static string|bool $key    = false;
    private static string      $cipher = 'AES-128-CTR';

    public static function encrypt(string $plaintext): string
    {
        self::$key = Config::get('system.key_crypt');

        if(!self::$key) {
            throw new Exception(
                'Encryption key not set in config ou .env (SCOOB_KEY_CRYPT)'
            );
        }

        $iv        = random_bytes(openssl_cipher_iv_length(self::$cipher));
        $encrypted = openssl_encrypt($plaintext, self::$cipher, self::$key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt(string $ciphertext): string
    {
        self::$key = Config::get('system.key_crypt');

        if(!self::$key) {
            throw new Exception(
                'Encryption key not set in config ou .env (SCOOB_KEY_CRYPT)'
            );
        }

        $data      = base64_decode($ciphertext);
        $ivLength  = openssl_cipher_iv_length(self::$cipher);
        $iv        = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        return openssl_decrypt($encrypted, self::$cipher, self::$key, 0, $iv);
    }
}