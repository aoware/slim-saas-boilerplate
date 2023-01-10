<?php

namespace helpers;

class encrypt_decrypt {

    protected $method = 'aes-256-cbc-hmac-sha256';

    private $key;

    public function __construct() {

        if (!defined(CONF_encryption_key)) {
            if (CONF_encryption_key != '') {
                $this->key = CONF_encryption_key;
                return;
            }
        }

        die("CONF_encryption_key is not set correctly");

    }

    public function encrypt($data) {

        $iv = openssl_random_pseudo_bytes($this->iv_bytes());
        return bin2hex($iv) . openssl_encrypt($data, $this->method, $this->key, 0, $iv);

    }

    // decrypt encrypted string
    public function decrypt($data) {

        $iv_strlen = 2  * $this->iv_bytes();

        if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
            list(, $iv, $crypted_string) = $regs;
            if (ctype_xdigit($iv) && (strlen($iv) % 2 == 0)) {
                return openssl_decrypt($crypted_string, $this->method, $this->key, 0, hex2bin($iv));
            }
        }
        return FALSE; // failed to decrypt
    }

    protected function iv_bytes() {

        return openssl_cipher_iv_length($this->method);
    }

}