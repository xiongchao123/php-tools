<?php

require "../autoload.php";

use Tool\Support\Str;

$c_str = "php�ַ���";
$c_full_str = "��ȫ�ַ���";
$j_str = "php-˽����ŤǤ�";
$j_full_str= "˽����ŤǤ�";
$e_str = "Hello PHP!";
$urdu_str = "??? ??? ???????? ???";

var_dump(Str::isContainChinese($c_str));  // need be true
var_dump(Str::isCompletelyChinese($c_str));  // need be false
// result  true  false

var_dump(Str::isContainChinese($c_full_str));  // need be true
var_dump(Str::isCompletelyChinese($c_full_str)); // need be true
// result  true  true

var_dump(Str::isContainChinese($j_str));  // need be true
var_dump(Str::isCompletelyChinese($j_str)); // need be false
// result  true  false

var_dump(Str::isContainChinese($e_str));  // need be false
var_dump(Str::isCompletelyChinese($e_str)); // need be false
// result  false  false

var_dump(Str::isContainChinese($urdu_str));  // need be false
var_dump(Str::isCompletelyChinese($urdu_str)); // need be false
// result  false  false

var_dump(Str::isContainChinese("  "));  // need be false
var_dump(Str::isCompletelyChinese("   ")); // need be false
