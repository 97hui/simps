<?php

declare(strict_types=1);

namespace App\VO;

class SaveResumeVO
{

    /**
     * 用户ID
     *
     * @var int
     */
    public $uid;

    /**
     * 来源
     *
     * @var int
     */
    public $deliverSource = 999;

    /**
     * 创建人
     *
     * @var int
     */
    public $createId;

    /**
     * icdc的简历ID
     *
     * @var int
     */
    public $icdcResumeId;

    /**
     * 简历ID
     *
     * @var string
     */
    public $resumeId;

    /**
     * 修改人
     *
     * @var int
     */
    public $updateId;

    /**
     * 简历内容
     *
     * @var array
     */
    public $content;

}
