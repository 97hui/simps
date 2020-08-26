<?php

declare(strict_types=1);

namespace App\Base;

use Simps\Context;
use Swoole\Http\{Request as SwRequest, Response as SwResponse};

/**
 * 基准控制类
 */
class Controller
{

    /**
     * 请求体对象
     *
     * @var SwRequest
     */
    public $request;

    /**
     * 响应体对象
     *
     * @var SwResponse
     */
    public $response;

    /**
     * 当前用户身份
     *
     * @var string
     */
    private $uid;

    /**
     * 每次请求的唯一id
     *
     * @var string
     */
    private $uuid;

    /**
     * 请求开始
     */
    public function __construct()
    {
        $this->uuid = $this->makeUuid();
        $this->request = Context::get('SwRequest');
        $this->response = Context::get('SwResponse');
        switch (SERVICE_TYPE) {
            case 'rpc':
                $this->requestAuth();
                break;
            case 'web':
                // TODO WEB server 的服务认证实现，并设置当前用户uid信息
                $this->setUid(1);
                break;
        }
        Context::set('RequestHeader', $this->getRequestHeader());
        $this->commonResponseHeader();
    }

    /**
     * 获取请求头信息
     *
     * @return array
     */
    public function getRequestHeader(): array
    {
        return [
            'log_id' => $this->uuid,
            'method' => $this->request->server['request_method'] ?? '',
            'url'    => $this->request->server['request_uri'] ?? '',
            'time'   => $this->request->server['request_time_float'] ?? '',
            'ip'     => $this->request->server['remote_addr'] ?? '',
            'uid'    => $this->getUid()
        ];
    }

    /**
     * 生成请求唯一ID
     *
     * @return void
     */
    private function makeUuid()
    {
        list($usec,) = explode(' ', microtime());
        $usec = sprintf('%06d', intval(1000000 * $usec));
        return date('YmdHis') . $usec;
    }

    /**
     * 请求头部认证，并设置当前用户uid信息
     *
     * @return void
     */
    protected function requestAuth()
    {
        $signature = $this->request->header['x-signature'] ?? '';
        $uid = $this->request->header['x-uid'] ?? '';
        $timestamp = $this->request->header['x-timestamp'] ?? '';
        if (!$timestamp || ('dev' != APP_ENV && abs($timestamp - time()) > 300)) { // 时间戳失效
            $this->response->status(500, 'Timestamp invalidation');
            return $this->response->end();
        }
        if (!$uid || !$signature || ('dev' != APP_ENV && $signature != md5($uid . $timestamp . APP_KEY))) { // 认证失败
            $this->response->status(500, 'Authentication failed');
            return $this->response->end();
        }
        $this->setUid($uid);
    }

    /**
     * 设置当前用户身份
     *
     * @param string $uid
     * @return void
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * 获取当前用户身份
     *
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * 设置公用响应头信息
     *
     * @param Response $response
     * @return void
     */
    public function commonResponseHeader()
    {
        $this->response->setHeader('Server', 'talent');
    }

    /**
     * json 响应体
     *
     * @param Response $response
     * @param mixed $data
     * @param int   $options
     * @return void
     */
    public function json($data, int $options = 0)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $response = new Response();
        $response->setResults($data);
        $response->setRequestId($this->uuid);
        $json = json_encode($response->toArray(), $options);
        Log::info(sprintf('response: %s', $json));
        $this->response->end($json);
    }

    /**
     * 错误信息响应
     *
     * @param [type] $errNo
     * @param string $errMsg
     * @param integer $options
     * @return void
     */
    public function error(int $errNo, string $errMsg, int $options = 0)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $response = new Response();
        $response->setErrNo($errNo ?: 500);
        $response->setErrMsg($errMsg);
        $response->setRequestId($this->uuid);
        Log::error($errMsg);
        $this->response->end(json_encode($response->toArray(), $options));
    }

    /**
     * 打印数据
     *
     * @param $data
     * @return void
     */
    public function dump($data)
    {
        $this->response->write($data);
        $this->response->end();
    }

    /**
     * 从application/josn格式中获取参数
     *
     * @return array
     */
    public function getContentFromJson()
    {
        $params = json_decode($this->request->rawContent(), true) ?? [];
        Log::info(sprintf('request params: %s', json_encode($params)));
        return $params;
    }
}
