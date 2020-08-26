<?php

declare(strict_types=1);

namespace App\Rpc;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

class Rpc
{

    /**
     * 发起调用请求
     *
     * @param string $srvName
     * @param array $params
     * @return array
     * @throws Exception    
     */
    public static function request(string $srvName, array $params): array
    {
        $config = config('rpc.' . $srvName);
        if (empty($config) || empty($config['url'])) {
            throw new Exception(sprintf('Server name %s is not configured!', $srvName));
        }
        $timeout = $config['timeout'] ?: 2;
        $url = $config['url'];
        $client = new Client(['timeout' => $timeout]);
        $headers = ['Accept' => 'application/json'];
        try {
            $response = $client->request('POST', $url, [
                'json' => $params,
                'allow_redirects' => false,
                'headers' => $headers
            ]);
            $content = $response->getBody()->getContents();
            $rs = json_decode($content, true) ?: [];
            return $rs;
        } catch (TransferException $e) {
            echo $e->getMessage();
            throw new Exception('Request Failed!');
        }
    }

    /**
     * 标准化rpc调用和返回结构
     *
     * @param string $srvName
     * @param array $params
     * @return mixed
     */
    public static function rpc(string $srvName, array $params, array $headers = [])
    {
        $headers = array_merge([
            'ip' => current(swoole_get_local_ip()) ?: '',
        ], $headers);
        $request = [
            'request' => $params,
            'headers' => $headers
        ];
        $rs = self::request($srvName, $request);
        if (empty($rs['response']) || !isset($rs['response']['err_no'])) {
            throw new Exception('Illegal Response!');
        }
        if (0 != $rs['response']['err_no']) {
            throw new Exception($rs['response']['err_msg'] ?? 'Request error!');
        }
        return $rs['response']['results'] ?? null;
    }
}
