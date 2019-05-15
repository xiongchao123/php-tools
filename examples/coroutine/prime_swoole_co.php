<?php

require "../autoload.php";

/*
 *  ����дһ�����㷨������һ����������֮��������ͣ�������������Ϊ[1,10000]��
    ����һ����������Ϊ[1,1000��]
    ���׶�����������Ϊ[1,100����]
 */

/*
 *  ��Ҫ�������⿪��
    ��һ�����⣬��ô����һ��������������
    �ڶ������⣬��ô������ǰ�ļ����������������ٶȣ�
    ���������⣬��ô������õ������ܽ��мƻ���
    ���ĸ����⣬��ô��֯�ֲ�ʽ��Ⱥ���м���
 */

function isPrimeNumber($number)
{
    if ($number < 3) {
        return $number > 1;
    }

    $mod_six = $number % 6;

    // ����6�ı��������һ����������
    if ($mod_six != 1 && $mod_six != 5) {
        return false;
    }

    $sqrt = sqrt($number);

    for ($i = 5; $i <= $sqrt; $i += 6) {
        if ($number % $i == 0 || $number % ($i + 2) == 0) {
            return false;
        }
    }
    return true;
}

function getPrimeSum($begin, $end)
{
    $primeSum = 0;

    for ($i = $begin; $i <= $end; $i++) {
        if (isPrimeNumber($i)) {
            $primeSum += $i;
        }
    }

    printf("%d --- %d 's prime sum is %d\n", $begin, $end, $primeSum);
}

$start = microtime(true);

$startCoroutineNumber = 1000 * 10000;
$beginNumber = 2;
$endNumber = 1000 * 10000;

if ($endNumber < $startCoroutineNumber) {
    getPrimeSum($beginNumber, $endNumber);
} else {
    // swoole Э��ִ��
    $n = ceil($endNumber / $startCoroutineNumber);

    for ($i = 0; $i < $n; $i++) {
        $beginNum = $i * $startCoroutineNumber + 1;
        if ($i < $n - 1) {
            $endNum = ($i + 1) * $startCoroutineNumber;
        } else {
            $endNum = $endNumber;
        }

        go(function () use ($beginNum, $endNum) {
            getPrimeSum($beginNum, $endNum);
        });
    }
}

$end = microtime(true);

print number_format($end - $start, 10, '.', '') . " seconds" . PHP_EOL;