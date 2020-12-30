<?php

namespace Tool\Lock;

interface DistributedLock
{
    /**
     * 获取锁
     * @param string $requestId
     * @param string $key
     * @param int $expiresTime
     * @return bool
     */
    public function lock(string $requestId, string $key, int $expiresTime): bool;

    /**
     * 释放锁
     * @param string $key
     * @param string $requestId
     * @return bool
     */
    public function releaseLock(string $key, string $requestId): bool;
}