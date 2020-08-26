<?php

declare(strict_types=1);

namespace App\Base;

use Redis;
use Simps\DB\Redis as RedisPool;

class RedisClient
{

    /**
     * redis 连接池
     *
     * @var RedisPool
     */
    protected $redisPool;

    /**
     * redis 连接对象
     *
     * @var Redis
     */
    protected $redis;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->redisPool = RedisPool::getInstance();
        $this->redis = $this->redisPool->getConnection();
    }

    /**
     * 获取一个redis连接对象
     *
     * @return Redis
     */
    public function getRedis(): Redis
    {
        return $this->redis;
    }

    /**
     * 释放当前连接回连接池
     *
     * @return void
     */
    public function close()
    {
        $this->redisPool->close($this->redis);
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        $this->close();
    }
}
