<?php

require "../autoload.php";

/*
 *  请书写一个简单算法，计算一个数字区间之间的质数和，假设数字区间为[1,10000]。
    进阶一：区间增大为[1,1000万]
    进阶二：区间增大为[1,100万亿]
 */

/*
 *  先要把问题拆解开：
    第一个问题，怎么计算一个数字是质数；
    第二个问题，怎么利用先前的计算结果来提升计算速度；
    第三个问题，怎么最大化利用单机性能进行计划；
    第四个问题，怎么组织分布式集群进行计算
 */

function isPrimeNumber($number)
{
    if ($number < 3) {
        return $number > 1;
    }

    $mod_six = $number % 6;

    // 不在6的倍数两侧的一定不是质数
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
    // 协程执行
    $scheduler = new \Tool\Kernel\Coroutine\Scheduler(); // 实例化一个调度器
    $n = ceil($endNumber / $startCoroutineNumber);

    for ($i = 0; $i < $n; $i++) {
        $beginNum = $i * $startCoroutineNumber + 1;
        if ($i < $n - 1) {
            $endNum = ($i + 1) * $startCoroutineNumber;
        } else {
            $endNum = $endNumber;
        }

        $scheduler->addTask(getPrimeSumTask($beginNum, $endNum)); // 添加不同的闭包函数作为任务
    }

    $scheduler->run();
}

$end = microtime(true);

print number_format($end - $start, 10, '.', '') . " seconds" . PHP_EOL;