<?php

        class MCrypt
        {
                private $iv = 'bitcbi0876543219'; #Same as in JAVA
//                private $key = '9123456780ibcdea'; #Same as in JAVA
                private $key = 'IXXBJPNIEQ2QAWXN'; #Same as in JAVA


                function __construct()
                {
                }
                /*
                function addpadding($string, $blocksize = 16){
                    $len = strlen($string);
                    $pad = $blocksize - ($len % $blocksize);
                    $string .= str_repeat(chr($pad), $pad);
                    return $string;
                    
                   
                }
                
                function encrypt($str) {
                    $iv = $this->iv;
                    $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $this->addpadding($str), MCRYPT_MODE_CBC, $iv);
                    return $crypttext;
                }
                
                function decrypt($code) {
                    $iv = $this->iv;
                    $str = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $code, MCRYPT_MODE_CBC, $iv);
                    return $str;
                }*/
                
                
                function encrypt($data) {
                    $blocksize = 16;
                    $pad = $blocksize - (strlen($data) % $blocksize);
                    $data = $data . str_repeat(chr($pad), $pad);
                    return (String)trim(bin2hex(@mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $data, MCRYPT_MODE_CBC, $this->iv)));
                }

                function decrypt($code) {
                    if(!$code) return '';
                    $result = @mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $this->hex2bin(trim($code)), MCRYPT_MODE_CBC, $this->iv);
                    return trim($result);
                }
                
                function decryptTrim($code) {
                    if(!$code) return '';
                    $result = @mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $this->hex2bin(trim($code)), MCRYPT_MODE_CBC, $this->iv);
                    $result = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result);
                    return trim($result);
                }
                
                function decryptInt($code) {
                    if(!$code) return '';
                    $result = @mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $this->hex2bin(trim($code)), MCRYPT_MODE_CBC, $this->iv);
                    return (int)trim($result);
                }

                function hex2bin($hex_string) {
                    return pack('H*', $hex_string);
                }

        }