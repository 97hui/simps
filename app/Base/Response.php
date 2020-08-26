<?php

declare(strict_types=1);

namespace App\Base;

class Response
{

    /**
     * 请求IDKey
     *
     * @var string
     */
    protected $requestIdKey = 'request_id';

    /**
     * 错误状态Key
     *
     * @var string
     */
    protected $errNoKey = 'err_no';

    /**
     * 错误消息Key
     *
     * @var string
     */
    protected $errMsgKey = 'err_msg';

    /**
     * 数据结果集Key
     *
     * @var string
     */
    protected $resultsKey = 'results';

    /**
     * 错误状态码
     *
     * @var integer
     */
    protected $errNo = 0;

    /**
     * 错误消息提示
     *
     * @var string
     */
    protected $errMsg = '';

    /**
     * 数据结果集
     *
     * @var mixed
     */
    protected $results = null;

    /**
     * 请求ID
     *
     * @var string
     */
    protected $requestId = '';

    /**
     * @param int $errNo
     * @return void
     */
    public function setErrNo(int $errNo)
    {
        $this->errNo = $errNo;
    }
    
    /**
     * @param string $errMsg
     * @return void
     */
    public function setErrMsg(string $errMsg)
    {
        $this->errMsg = $errMsg;
    }

    /**
     * @param $results
     * @return void
     */
    public function setResults($results)
    {
        $this->results = $results;
    }

    /**
     * @param $requestId
     * @return void
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    /**
     * 转换成数组
     *
     * @return void
     */
    public function toArray()
    {
        return [
            $this->requestIdKey   => $this->requestId,
            $this->errNoKey   => $this->errNo,
            $this->errMsgKey  => $this->errMsg,
            $this->resultsKey => $this->results,
        ];
    }
}
