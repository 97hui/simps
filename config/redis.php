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
    'host' => getenv('REDIS_HOST') ?: '',
    'port' => getenv('REDIS_PORT') ? intval(getenv('REDIS_PORT')) : 6379,
    'auth' => getenv('REDIS_AUTH') ?: '',
    'db_index' => getenv('REDIS_DB_INDEX') ?: 0,
    'time_out' => 1,
    'size' => 64,
];
