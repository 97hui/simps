<?php

declare(strict_types=1);

return [
    'resume' => [
        'url' => getenv('RESUME_URL') ?: '',
        'timeout' => getenv('RESUME_TIMEOUT') ?: 2,
    ],
];
