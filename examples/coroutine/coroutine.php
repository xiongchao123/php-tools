<?php

require "../autoload.php";

function task1() {
    for ($i = 1; $i <= 10; ++$i) {
        echo "This is task 1 iteration $i.\n";
        yield; // �����ó�CPU��ִ��Ȩ
    }
}

function task2() {
    for ($i = 1; $i <= 5; ++$i) {
        echo "This is task 2 iteration $i.\n";
        yield; // �����ó�CPU��ִ��Ȩ
    }
}

$scheduler = new \Tool\Kernel\Coroutine\Scheduler(); // ʵ����һ��������
$scheduler->addTask(task1()); // ��Ӳ�ͬ�ıհ�������Ϊ����
$scheduler->addTask(task2());
$scheduler->run();