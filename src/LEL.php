<?php

namespace Piccard\LEL;

use Illuminate\Support\Facades\DB;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Piccard\LEL\MySQLHandler;

/**
 * Class LEL
 * @package piccard/lara-mysql-log
 */
class LEL
{

    private static $instance;
    protected $allLogLevels;
    protected $logger;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get the value of Logger
     *
     * @return mixed
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Set the value of Logger
     *
     * @param mixed logger
     *
     * @return self
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Get the value of All Log Levels
     *
     * @return mixed
     */
    public function getAllLogLevels()
    {
        return $this->allLogLevels;
    }

    /**
     * Set the value of All Log Levels
     *
     * @param mixed allLogLevels
     *
     * @return self
     */
    public function setAllLogLevels($allLogLevels)
    {
        $this->allLogLevels = $allLogLevels;
        return $this;
    }

    /**
     * main entry point for Monolog configuration
     *
     * @param  Logger $logger [description]
     * @return void
     */
    public static function configureMonolog($logger)
    {
        $lel = self::getInstance();
        $lel->setLogger($logger);
        $lel->setAllLogLevels(Logger::getLevels());

        // assign server-name to extra
        $logger->pushProcessor(function ($record) {
            $record['extra']['server'] = $_SERVER['SERVER_NAME'];
//            $record['extra']['server'] = gethostname();
            return $record;
        });

        // FILES
        $lel->initFileHandlers();

        // DB
        if (config('laravel-easy-log.db.logging_enabled')) {
            $lel->initDbHandler($logger);
        }

        // STDOUT / STDERR
        $lel->initStdHandlers();

        // MAIL
        $lel->initMailHandler();

        return $logger;
    }

    /**
     * initialize mail-handler
     *
     * @return void
     */
    private function initMailHandler()
    {
        $lel    = self::getInstance();
        $logger = $lel->getLogger();

        if (config('laravel-easy-log.mail.logging_enabled')) {
            $bubble         = config('laravel-easy-log.mail.bubble');
            $dateFormat     = config('laravel-easy-log.mail.date_format');
            $output         = config('laravel-easy-log.mailoutput_format');
            $level          = config('laravel-easy-log.mail.log_level');
            $from           = config('laravel-easy-log.mail.from');
            $to             = config('laravel-easy-log.mail.to');
            $subject        = config('laravel-easy-log.mail.subject');
            $maxColumnWidth = config('laravel-easy-log.mail.maxColumnWidth');

            // set formatter
            $formatter = new LineFormatter($output, $dateFormat);
            $handler   = new NativeMailerHandler($to, $subject, $from, $level, $bubble, $maxColumnWidth);

            $handler->setFormatter($formatter);
            $logger->pushHandler($handler);
        }
    }

    /**
     * initialize stdout-, stderr-handlers
     *
     * @return void
     */
    private function initStdHandlers()
    {
        $lel    = self::getInstance();
        $logger = $lel->getLogger();

        foreach (['out', 'err'] as $std) {
            if (config('laravel-easy-log.std.' . $std . '.logging_enabled')) {
                $bubble     = config('laravel-easy-log.std.' . $std . '.bubble');
                $dateFormat = config('laravel-easy-log.std.' . $std . '.date_format');
                $output     = config('laravel-easy-log.std.' . $std . '.output_format');
                $level      = config('laravel-easy-log.std.' . $std . '.log_level');

                // set formatter
                $formatter = new LineFormatter($output, $dateFormat);
                $handler   = new StreamHandler('php://std' . $std, $lel->getLevelInt($level), $bubble);
                $handler->setFormatter($formatter);
                $logger->pushHandler($handler);
            }
        }
    }

    /**
     * initialize the file-handlers
     *
     * @return void
     */
    private function initFileHandlers()
    {
        $lel    = self::getInstance();
        $logger = $lel->getLogger();

        if (config('laravel-easy-log.file.logging_enabled')) {
            $bubble     = config('laravel-easy-log.file.bubble');
            $dateFormat = config('laravel-easy-log.file.date_format');
            $output     = config('laravel-easy-log.file.output_format');
            $logLevels  = config('laravel-easy-log.file.log_levels');
            $logDir     = config('laravel-easy-log.file.log_dir');
            $logDir     = rtrim($logDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        } // default Laravel-Logging
        else {
            $bubble     = true;
            $dateFormat = "Y-m-d H:i:s";
            $output     = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
            $logLevels  = [env('APP_LOG_LEVEL', 'DEBUG')];
            $logDir     = storage_path() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
        }

        // set formatter
        $formatter = new LineFormatter($output, $dateFormat);
        foreach ($logLevels as $level) {
            $logFile = $logDir . strtolower($level) . '.log';
            // depends on config('app.log')
            $lel->setFileHandler($logger, $bubble, $lel->getLevelInt($level), $logFile, $formatter);
        }
    }

    /**
     * get integer of level
     *
     * @param string $level
     * @return int  level
     */
    private function getLevelInt($level)
    {
        $lel = self::getInstance();
        return $lel->getAllLogLevels()[strtoupper($level)];
    }

    /**
     * set the file-handlers
     *
     * @param Logger $logger [description]
     * @param int $level [description]
     * @param string $logFile [description]
     * @param Formatter $formatter [description]
     */
    private function setFileHandler($logger, $bubble, $level, $logFile, $formatter)
    {
        switch (config('app.log')) {
            case 'single':
                $handler = new StreamHandler($logFile, $level, $bubble);
                break;

            case 'daily':
                $logMaxFiles = (int) config('laravel-easy-log.file.daily.log_max_files');
                $handler     = new RotatingFileHandler($logFile, $logMaxFiles, $level, $bubble);
                break;

            case 'syslog':
                $handler = new SyslogHandler(config('laravel-easy-log.file.syslog.ident'), config('laravel-easy-log.file.syslog.facility'), $level, $bubble);
                break;

            case 'errorlog':
                // @see  http://de2.php.net/manual/en/function.error-log.php
                $handler = new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, $level, $bubble);
                break;
        }

        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);
        return $handler;
    }

    /**
     * initialize the MySql-Logger
     *
     * @param  Logger $logger Monolog-Logger
     * @param  int $level level from env
     */
    private function initDbHandler($logger, $level = Logger::DEBUG)
    {
        // use default-connection
        if (config('laravel-easy-log.db.use_default_connection')) {
            $pdo = DB::connection()->getPdo();
        } // create a custom connection
        else {
            $pdo = self::getDbConnection()->getConnection()->getPdo();
        }

        $table   = config('laravel-easy-log.db.table', 'lel');
        $columns = config('laravel-easy-log.db.columns');
        $app     = config('laravel-easy-log.db.app');

        $dbHandler = new MySQLHandler($pdo, $table, $level, $app, $columns);
        $logger->pushHandler($dbHandler);
    }

    /**
     * get a new DBConnection with paramters specified in laravel-easy-log.config
     *
     * @return DbConnectionOnTheFly
     */
    public static function getDbConnection()
    {
        return new DbConnectionOnTheFly([
            'driver'   => config('laravel-easy-log.db.driver', 'mysql'),
            'host'     => config('laravel-easy-log.db.host', 'localhost'),
            'port'     => config('laravel-easy-log.db.port', '3306'),
            'database' => config('laravel-easy-log.db.database'),
            'username' => config('laravel-easy-log.db.username'),
            'password' => config('laravel-easy-log.db.password'),
        ]);
    }

}
