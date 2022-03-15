<?php

namespace Core\Security;

use Exception;

class Encryption{
    
    private String $key = "";


    public function __construct(string $key = "")
    {
        $this->key = $key;
    }

    
    /**
     * encrypt
     * Encrypt data with key given from object constructor.
     * Ex: encrypt("my_text", "aes-256-cbc") return "encrypted data"
     * @param  string $data
     * @param  string $cipher
     * @return string
     * @link To see available cypher : https://www.php.net/manual/fr/function.openssl-get-cipher-methods.php
     */
    function encrypt(String $data, String $cipher = "aes-256-cbc"): String {

        try {
            $encryption_key = base64_decode($this->key);
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

            $encrypted = openssl_encrypt($data, $cipher, $encryption_key, 0, $iv);
            return base64_encode($encrypted . '::' . $iv);

        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }
    
    
    /**
     * decrypt
     * Decrypt data with key given from object constructor.
     * Ex: decrypt("my_text_encrypted", "aes-256-cbc") return "decrypted data"
     * @param  string $data
     * @param  string $cipher
     * @return string
     * @link To see available cypher : https://www.php.net/manual/fr/function.openssl-get-cipher-methods.php
     */
    function decrypt(String $data, String $cipher = "aes-256-cbc"): String {
        try {
            $encryption_key = base64_decode($this->key);
            list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
            return openssl_decrypt($encrypted_data, $cipher, $encryption_key, 0, $iv);

        }catch (\Exception $th) {
            return $th->getMessage();
        }
    }
        
    /**
     * xor
     * Encrypt / Decrypt date with a key given from object constructor. 
     * Ex: xor("my_text") return "encrypted data"
     * @param  string $data
     * @return string
     */
    function xor(String $data): String
    {
        try {
            $text = $data;
            $outText = '';
            for($i=0; $i<strlen($text); )
            {
                for($j=0; ($j < strlen($this->key) && $i < strlen($text)); $j++,$i++)
                {
                    $outText .= $text[$i] ^ $this->key[$j];
                }
            }
            return $outText;
            
        }catch (\Exception $th) {
            return $th->getMessage();
        }
    }
}