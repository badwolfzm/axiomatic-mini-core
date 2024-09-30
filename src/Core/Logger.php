<?php

namespace Core;

class Logger {
    private $logPath;
    private $logLevel;
    private $format;

    public function __construct($logConfig) {
        $this->logPath = $logConfig['path'];
        $this->logLevel = $logConfig['level'];
        $this->format = $logConfig['format'];
    }

    public function log($message, $level = 'INFO') {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = [
            'timestamp' => $timestamp,
            'level' => $level,
            'message' => $message
        ];

        if ($this->format === 'json') {
            $logMessage = json_encode($logEntry);
        } else {
            $logMessage = "[$timestamp] [$level]: $message";
        }

        file_put_contents($this->logPath, $logMessage . PHP_EOL, FILE_APPEND);
    }
}
