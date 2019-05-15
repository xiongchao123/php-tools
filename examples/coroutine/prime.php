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

    yield;
}

function getPrimeSumTask($begin, $end)
{
    printf("this is task:%d form %d to %d\n", $i, $begin, $end);
    yield from getPrimeSum($begin, $end);
}

$start = microtime(true);

$startCoroutineNumber = 100 * 10000;
$beginNumber = 2;
$endNumber = 1000 * 10000;

if ($endNumber < $startCoroutineNumber) {
    getPrimeSum($beginNumber, $endNumber);
} else {
    // Э��ִ��
    $scheduler = new \Tool\Kernel\Coroutine\Scheduler(); // ʵ����һ��������
    $n = ceil($endNumber / $startCoroutineNumber);

    for ($i = 0; $i < $n; $i++) {
        $beginNum = $i * $startCoroutineNumber + 1;
        if ($i < $n - 1) {
            $endNum = ($i + 1) * $startCoroutineNumber;
        } else {
            $endNum = $endNumber;
        }

        $scheduler->addTask(getPrimeSumTask($beginNum, $endNum)); // ��Ӳ�ͬ�ıհ�������Ϊ����
    }

    $scheduler->run();
}

$end = microtime(true);

print number_format($end - $start, 10, '.', '') . " seconds" . PHP_EOL;