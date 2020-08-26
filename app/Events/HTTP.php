<?php

declare(strict_types=1);

namespace App\Events;

use Simps\Application;
use Swoole\Http\Server;

class HTTP
{
    public static function onClose()
    {
        Application::println('close');
        file_put_contents('/var/log/http.log','close');
        // echo 2222;
        // exit;
    }
}