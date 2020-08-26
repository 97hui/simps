<?php

declare(strict_types=1);
/**
 * This file is part of Simps.
 *
 * @link     https://simps.io
 * @document https://doc.simps.io
 * @license  https://github.com/simple-swoole/simps/blob/master/LICENSE
 */
return [
    ['POST', '/api/resume/fileUpload', '\App\Controllers\ResumeController@fileUpload'],
    ['POST', '/api/resume/save', '\App\Controllers\ResumeController@save'],
    ['POST', '/api/resume/search', '\App\Controllers\ResumeController@search'],
    ['POST', '/api/resume/detail', '\App\Controllers\ResumeController@detail'],
    ['POST', '/api/erobot/saveDistributePositionInfo', '\App\Controllers\ERobotController@saveDistributePositionInfo'],
    ['POST', '/api/erobot/listChannel', '\App\Controllers\ERobotController@listChannel'],
];
