<?php
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('date.timezone', 'Asia/Shanghai');

error_reporting(E_ALL);

!defined('SWOOLE_HOOK_FLAGS') && define('SWOOLE_HOOK_FLAGS', SWOOLE_HOOK_ALL);
!defined('BASE_PATH') && define('BASE_PATH', __DIR__);
!defined('CONFIG_PATH') && define('CONFIG_PATH', BASE_PATH . '/config/');

require './vendor/autoload.php';

(new \Symfony\Component\Dotenv\Dotenv())->usePutenv()->load(__DIR__ . '/' . (getenv('ENV_FILE') ?: '.env'));

// 环境变量常量化
!defined('APP_ENV') && define('APP_ENV', getenv('APP_ENV') ?: 'dev');
!defined('APP_KEY') && define('APP_KEY', getenv('APP_KEY') ?: '');
!defined('SERVICE_TYPE') && define('SERVICE_TYPE', getenv('SERVICE_TYPE') ?: 'web'); // 服务类型 web、rpc
!defined('APP_PORT') && define('APP_PORT', getenv('APP_PORT') ? intval(getenv('APP_PORT')) : 9501); // 设置应用启用端口