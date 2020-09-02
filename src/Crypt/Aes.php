<?php

namespace Tool\Crypt;

/**
 * 密钥长度 128bits
 * 加密模式 AES-128-CBC
 * 填充方式 PKCS7Padding
 * 初始向量 iv 随机生成
 *
 * Class Aes
 * @package App\Libs\Crypt
 */
class Aes
{
    /**
     * 1.由于历史原因，JDK默认只支持不大于128 bits的密钥
     * 2.原本用户密钥保护码为16字节的随机字符串
     * @var string
     */
    protected static $method = 'AES-128-CBC';

    public static function encrypt($data, $key)
    {
        $ivLen = openssl_cipher_iv_length(static::$method);
        $iv = openssl_random_pseudo_bytes($ivLen);
        $text = openssl_encrypt($data, static::$method, $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $text);
    }

    public static function decrypt($text, $key)
    {
        // php7.0.11 版本的base64_decode有问题
        // base64_decode($text, true); 无效
        $cipherText = base64_decode($text, true);
        if ($cipherText === false) {
            return false;
        }
        $ivLen = openssl_cipher_iv_length(static::$method);
        $iv = substr($cipherText, 0, $ivLen);
        $cipherText = substr($cipherText, $ivLen);
        $data = openssl_decrypt($cipherText, static::$method, $key, OPENSSL_RAW_DATA, $iv);
        return $data;
    }
}