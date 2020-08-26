<?php

declare(strict_types=1);
/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */

namespace App\Controllers;

use App\Base\Controller;
use App\Services\ERobotService;
use Exception;

/**
 * e小宝-控制层
 */
class ERobotController extends Controller
{

    /**
     * e小宝业务逻辑层对象
     *
     * @var ERobotService
     */
    public $erobotService = null;

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->erobotService = new ERobotService();
    }

    /**
     * RPC-外网渠道分发数据保存接口
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function saveDistributePositionInfo()
    {
        try {
            $this->json($this->erobotService->saveDistributePositionInfo($this->getContentFromJson(), $this->getUid()));
        } catch (Exception $e) {
            $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * RPC-外网支持的分发渠道列表
     *
     * @return void
     */
    public function listChannel()
    {
        try {
            $this->json(array_values(config('channel')));
        } catch (Exception $e) {
            $this->error($e->getCode(), $e->getMessage());
        }
    }
    
    /**
     * WEB-e小宝获取当前渠道要分发的职位信息
     *
     * @return void
     */
    public function getDistributePositionInfo()
    {       
        try {
            $param = $this->getContentFromJson();
            if (!isset($param['channel_id'])) {
                throw new Exception('channel_id must');
            }
            $channelId = $param['channel_id'];
            $this->json($this->erobotService->getDistributePositionInfo($this->getUid(), $channelId));
        } catch (Exception $e) {
            $this->error($e->getCode(), $e->getMessage());
        }
    }
}
