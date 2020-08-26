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

/**
 * 简历控制层
 */
class ResumeController extends Controller
{

    public function fileUpload()
    {
        $this->json(config('database'));
    }

    public function save()
    {
    }

    public function search()
    {
        
    }

    public function detail()
    {
    }
}
