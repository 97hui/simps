<?php

declare(strict_types=1);

namespace App\Base;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Simps\Context;

class Log
{
    /**
     * 日志级别
     *
     * @var int
     */
    private $level;

    /**
     * 日期格式化
     *
     * @var string
     */
    private $dateFormat;

    /**
     * 日志输出格式化
     *
     * @var string
     */
    private $output;

    /**
     * 日志实例化对象
     * 
     * @var Logger
     */
    private $logger;

    /**
     * 日志名称
     *
     * @var string
     */
    private $logName;

    /**
     * 日志文件
     *
     * @var string
     */
    private $logFile;

    /**
     * 单例
     * 
     * @var Log
     */
    static $instance = null;

    /**
     * 获取实例
     *
     * @return Log
     */
    public static function newInstance($config = [])
    {
        if (empty(self::$instance)) {
            $name = $config['name'] ?? '';
            $file = $config['file'] ?? '';
            $level = $config['level'] ?? '';
            $dateFormat = $config['dateFormat'] ?? '';
            $format = $config['format'] ?? '';
            self::$instance = new self($name, $file, $level, $dateFormat, $format);
        }
        return self::$instance;
    }

    /**
     * __construct
     */
    private function __construct($name = '', $file = '', $level = '', $dateFormat = '', $format = '')
    {
        $this->setLevel($level)->setDateFormat($dateFormat)->setOutput($format)->setLogName($name);
        $this->logger = new Logger($this->logName);
        $this->setLogFile();
    }

    /**
     * 设置日志名称
     *
     * @param string $logName
     * @return Log
     */
    public function setLogName($logName = '')
    {
        $this->logName = $logName ?: (getenv('LOG_NAME') ?: '');
        return $this;
    }

    /**
     * 设置日志文件
     *
     * @param string $level
     * @return Log
     */
    public function setLogFile($logFile = '')
    {
        $logFile = $logFile ?: (getenv('LOG_FILE') ?: '');
        switch (strtoupper($this->logName)) {
            case 'DAILY':
                $logFile = sprintf($logFile, date('Ymd'));
                break;
        }
        if ($this->logFile != $logFile) {
            $this->logFile = $logFile;
            $stream = new StreamHandler($this->logFile, $this->level, true, 0644);
            $stream->setFormatter(new LineFormatter($this->output, $this->dateFormat));
            $this->logger->close();
            $this->logger->setHandlers([$stream]);
        }

        return $this;
    }

    /**
     * 设置日志级别
     *
     * @param string $level
     * @return Log
     */
    public function setLevel($level = '')
    {
        $this->level = $level ?: (getenv('LOG_LEVEL') ?: Logger::DEBUG);
        return $this;
    }

    /**
     * 设置日期格式化
     *
     * @param string $dateFormat
     * @return Log
     */
    public function setDateFormat($dateFormat = '')
    {
        $this->dateFormat = $dateFormat ?: (getenv('LOG_DATE_FORMAT') ?: LineFormatter::SIMPLE_DATE);
        return $this;
    }

    /**
     * 设置日志输出格式
     *
     * @param string $output
     * @return Log
     */
    public function setOutput($output = '')
    {
        $this->output = $output ?: (getenv('LOG_OUTPUT') ?: LineFormatter::SIMPLE_FORMAT);
        return $this;
    }

    /**
     * 设置日志上下文信息
     *
     * @param string $logId
     * @param array $header
     */
    public static function context()
    {
        return Context::get('RequestHeader') ?? [];
    }

    /**
     * 输出 info 日志
     *
     * @param string $str
     */
    public static function info(string $str, array $context = [])
    {
        self::newInstance()->setLogFile()->logger->info($str, $context ?: self::context());
    }

    /**
     * 输出 error 日志
     *
     * @param string $str
     */
    public static function error(string $str, array $context = [])
    {
        self::newInstance()->setLogFile()->logger->error($str, $context ?: self::context());
    }

    /**
     * 输出 warning 日志
     *
     * @param string $str
     */
    public static function warning(string $str, array $context = [])
    {
        self::newInstance()->setLogFile()->logger->warning($str, $context ?: self::context());
    }

    /**
     * 输出 debug 日志
     *
     * @param string $str
     */
    public static function debug(string $str, array $context = [])
    {
        self::newInstance()->setLogFile()->logger->debug($str, $context ?: self::context());
    }

    /**
     * 输出 notice 日志
     *
     * @param string $str
     */
    public static function notice(string $str, array $context = [])
    {
        self::newInstance()->setLogFile()->logger->notice($str, $context ?: self::context());
    }

    /**
     * 输出 alert 日志
     *
     * @param string $str
     */
    public static function alert(string $str, array $context = [])
    {
        self::newInstance()->setLogFile()->logger->alert($str, $context ?: self::context());
    }

    /**
     * 输出 critical 日志
     *
     * @param string $str
     */
    public static function critical(string $str, array $context = [])
    {
        self::newInstance()->setLogFile()->logger->critical($str, $context ?: self::context());
    }

    /**
     * 输出 emergency 日志
     *
     * @param string $str
     */
    public static function emergency(string $str, array $context = [])
    {
        self::newInstance()->setLogFile()->logger->emergency($str, $context ?: self::context());
    }
}
