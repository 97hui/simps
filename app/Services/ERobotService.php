<?php

declare(strict_types=1);

namespace App\Services;

use App\Base\RedisClient;
use Exception;

class ERobotService
{

    /**
     * 分发存储的cache key
     */
    const DISTRIBUTE_JD_KEY = 'distribute_jd_%s_%s';

    /**
     * 保存用户当前要分发的职位信息
     *
     * @param array $param
     * @param string $uid
     * @return bool
     */
    public function saveDistributePositionInfo($param, $uid): bool
    {

        $channels = config('channel');
        $channelId = $param['channel_id'] ?? 0;
        if (!$channelId || !$channels || !isset($channels[$channelId])) {
            throw new Exception('Channel error!');
        }
        $positionInfo = $param['position_info'] ?? [];
        if (!$positionInfo || !is_array($positionInfo)) {
            throw new Exception('Position info error!');
        }
        $defaultPositionInfo = config('app.e_bot_distribute_default') ?: [];
        foreach ($defaultPositionInfo as $key => $value) {
            if (isset($positionInfo[$key])) {
                $defaultPositionInfo[$key] = $positionInfo[$key];
            }
        }
        $key = sprintf(self::DISTRIBUTE_JD_KEY, $uid, $channelId);
        $client = new RedisClient();
        $redis = $client->getRedis();
        return $redis->set($key, json_encode($defaultPositionInfo), 86400); // 设置一天时间的缓存
    }

    /**
     * 获取当前用户当前渠道的分发职位信息
     *
     * @param $uid
     * @param $channelId
     * @return array
     */
    public function getDistributePositionInfo($uid, $channelId): array
    {
        $channels = config('channel');
        if (!$channelId || !$channels || !isset($channels[$channelId])) {
            throw new Exception('Channel error!');
        }
        $key = sprintf(self::DISTRIBUTE_JD_KEY, $uid, $channelId);
        $client = new RedisClient();
        $redis = $client->getRedis();
        $data = $redis->get($key);
        $redis->del($key);
        return $data ? json_decode($data, true) : [];
    }
}
