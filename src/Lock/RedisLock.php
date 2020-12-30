<?php

namespace Tool\Lock;

/**
 * Redis实现分布式锁
 *
 * Class RedisLock
 * @package Tool\Lock
 */
class RedisLock implements DistributedLock
{
    protected $redis;

    /**
     * RedisLock constructor.
     * @param \Redis $redis
     */
    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    /**
     * @inheritDoc
     */
    public function lock(string $requestId, string $key, int $expiresTime): bool
    {
        return $this->redis->eval(
            $this->luaLockScript(), [$key, $requestId, $expiresTime], 1
        );
    }

    /**
     * @inheritDoc
     */
    public function releaseLock(string $key, string $requestId): bool
    {
        return $this->redis->eval($this->luaReleaseLock(), [
            $key, $requestId
        ], 1);
    }

    /**
     * KEYS[1] - The lock name
     * ARGV[1] - The lock value
     * ARGV[2] - The lock expire time
     *
     * @return string
     */
    protected function luaLockScript()
    {
        // 早期版本的redis如果不支持set同时设置EX和NX可以写下IF ELSE
        // LUA脚本可以保证操作的原子性
        return <<<'LUA'
return redis.call('set',KEYS[1],ARGV[1],'EX',ARGV[2],'NX')
LUA;
    }

    /**
     * KEYS[1] - The lock name
     * ARGV[1] - The lock value
     *
     * @return string
     */
    protected function luaReleaseLock()
    {
        return <<<'LUA'
if redis.call('get',KEYS[1]) == ARGV[1] then 
    return redis.call('del',KEYS[1]) 
else 
    return 0 
end
LUA;
    }
}