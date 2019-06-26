<?php

namespace Tool\Support;

class Str
{
    public static function angleConvert($str, $convert = 0)
    {
        $fullAngleArr = array(
            '��', '��', '��', '��', '��', '��', '��', '��', '��', '��',
            '��', '��', '��', '��', '��', '��', '��', '��', '��', '��',
            '��', '��', '��', '��', '��', '��', '��', '��', '��', '��',
            '��', '��', '��', '��', '��', '��', '��', '��', '��', '��',
            '��', '��', '��', '��', '��', '��', '��', '��', '��', '��',
            '��', '��', '��', '��', '��', '��', '��', '��', '��', '��',
            '��', '��', '��', '��', '��', '��', '��', '��', '��', '��',
            '��', '��', '��', '��', '��', '��', '��', '��', '��', '��',
            '��', '��', '��', '��', '��', '��', '��', '��', '��', '��',
            '��', '��', '��', '��', '��', '��', '��', '��', '��', '��',
            '��', '��'
        );

        $semiangleArr = array(
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd',
            'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
            'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
            'y', 'z', '(', ')', '[', ']', '[', ']', '[', ']',
            '[', '"', '`', '`', '{', '}', '<', '>', '%', '+',
            '-', '-', '-', ':', '.', '.', '.', ',', '?', '!',
            '-', '|', '|', '"', ' ', '$', '@', '#', '^', '&',
            '*', '"'
        );
        if ($convert === 0) {
            return str_replace($fullAngleArr, $semiangleArr, $str);
        } else {
            return str_replace($semiangleArr, $fullAngleArr, $str);
        }
    }

    public static function getWordCount($str)
    {
        $str = preg_replace('/[\x80-\xff]{1,3}/', ' w ', $str);

        return count(array_filter(explode(" ", $str)));
    }

    public static function isContainChinese($str)
    {
        return preg_match('/[\x7f-\xff]/', $str) == 1;
    }

    public static function isCompletelyChinese($str){
        return preg_match('/^[\x7f-\xff]+$/', $str) == 1;
    }
}
