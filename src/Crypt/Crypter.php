<?php

namespace Tool\Crypt;

class Crypter
{
    public function createKeyPair()
    {
        $config = [
            'digest_alg' => 'sha1',
            'private_key_bits' => '1024',
            'private_key_type' => OPENSSL_KEYTYPE_RSA
        ];

        return openssl_pkey_new($config);
    }

    public function getPrivateKey($resource, $passphrase = NULL)
    {
        $privateKey = "";

        openssl_pkey_export($resource, $privateKey, $passphrase);

        return $privateKey;
    }

    public function getPublicKey($resource)
    {
        $pub = openssl_pkey_get_details($resource);
        return $pub['key'];
    }

    public function sign($data, $resource)
    {
        $signature = "";
        openssl_sign($data, $signature, $resource);
        return base64_encode($signature);
    }

    public function loadPrivateKey($key, $passphrase = NULL)
    {
        return openssl_pkey_get_private($key, $passphrase);
    }

    public function destroy($resource)
    {
        openssl_free_key($resource);
    }

    public function verifySignature($data, $signature, $publicKey)
    {
        $rawSignature = base64_decode($signature);
        return openssl_verify($data, $rawSignature, $publicKey);
    }

    public function verifyPrivateKey($publicKey, $privateKey, $passphrase = NULL)
    {
        $res = openssl_pkey_get_private($privateKey, $passphrase);
        if(!$res)
        {
            return false;
        }

        $crypted = "";
        $decrypted = "";

        openssl_public_encrypt('yuanben', $crypted, $publicKey);
        openssl_private_decrypt($crypted, $decrypted, $res);
        return $decrypted === 'yuanben';
    }

    public function printKey($key)
    {
        return preg_replace("/\n/", "<br />", $key);
    }
}