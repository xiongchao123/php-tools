<?php

require __DIR__ . "/../autoload.php";

$redis = new \Redis();
$redis->connect('127.0.0.1', 16379);
$redis->select(1);

$lock = new \Tool\Lock\RedisLock($redis);

for ($i = 0; $i < 10; $i++) {
    $requestId = uniqid();

    $res = $lock->lock($requestId, 'lock-temp-ttttt', 3);

    $res2 = $lock->releaseLock('lock-temp-ttttt',$requestId);
    sleep(1);
    var_dump($res);
    echo "release:".PHP_EOL;
    var_dump($res2);
}