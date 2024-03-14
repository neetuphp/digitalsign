<?php

namespace App\Helpers;

use phpseclib3\Crypt\RSA;
use phpseclib3\Math\BigInteger;

class PhpseclibHelper extends RSA
{
    public static function checkPhpseclib()
    {
        if (class_exists(RSA::class)) {
            return 'phpseclib is installed and working correctly.';
        } else {
            return 'phpseclib is not installed or there is an issue with the installation.';
        }
    }

    // public static function generateKeyPair()
    // {
    //     //$rsa = new RSA();

    //     // Create a new key pair
    //     $keyPair = $rsa->createKey();

    //     // Return the public and private keys
    //     return [
    //         'privateKey' => $keyPair['privatekey'],
    //         'publicKey' => $keyPair['publickey'],
    //     ];
    // }

    public function toString($type, array $options = [])
    {
        // Provide an implementation for the toString method
        // You can choose to implement it based on the $type and $options parameters
        // For example, you can check if $type is 'PKCS8' and provide the corresponding string representation

        // Example: return $this->toPEMString($type, $options);
    }
}





