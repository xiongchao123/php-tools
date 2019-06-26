<?php

require "../autoload.php";

function task1() {
    for ($i = 1; $i <= 10; ++$i) {
        echo "This is task 1 iteration $i.\n";
        yield; // 主动让出CPU的执行权
    }
}

function task2() {
    for ($i = 1; $i <= 5; ++$i) {
        echo "This is task 2 iteration $i.\n";
        yield; // 主动让出CPU的执行权
    }
}

$scheduler = new \Tool\Kernel\Coroutine\Scheduler(); // 实例化一个调度器
$scheduler->addTask(task1()); // 添加不同的闭包函数作为任务
$scheduler->addTask(task2());
$scheduler->run();