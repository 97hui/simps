<?php

declare(strict_types=1);

namespace App\Rpc;

use App\VO\SaveResumeVO;

class ResumeRpc extends Rpc
{
    /**
     * 服务名
     */
    const SRV_NAME = 'resume';

    /**
     * 获取简历信息
     *
     * @param array $resumeIds
     * @return array
     */
    public static function getResumeDetailByIds(array $resumeIds): array
    {
        $rs = self::rpc(self::SRV_NAME, ['c' => 'resumeDetail', 'm' => 'listDetailByIds', 'p' => ['tobResumeIds' => $resumeIds]]);
        return $rs ?: [];
    }

    /**
     * 保存简历信息
     *
     * @param SaveResumeVO $saveResumeVO
     * @return mixed
     */
    public static function saveResume(SaveResumeVO $saveResumeVO)
    {
        $params = [
            'uid'           => $saveResumeVO->uid,
            'deliverSource' => $saveResumeVO->deliverSource,
            'content'       => json_encode($saveResumeVO->content),
            'icdcResumeId'  => $saveResumeVO->icdcResumeId
        ];
        if ($saveResumeVO->resumeId) { // 修改简历
            $params['updateId'] = $saveResumeVO->updateId;
            $method = 'updateResume';
        } else { // 新增简历
            $params['createId'] = $saveResumeVO->createId;
            $method = 'saveResume';
        }
        $rs = self::rpc(self::SRV_NAME, ['c' => 'resume', 'm' => $method, 'p' => $params]);
        return $rs['tobResumeId'] ?? 0;
    }
}
