<?php

namespace SnapWizard\Helpers;

class Logger
{
    private string $_action = '';

    private const LEVEL_ERROR = 'error';
    private const LEVEL_INFO = 'info';
    private const LEVEL_NOTICE = 'notice';
    private const LEVEL_DEBUG = 'debug';
    private const LEVEL_WARNING = 'warning';
    private const LEVEL_CRITICAL = 'critical';

    public function setAction(string $action)
    {
        $this->_action = $action;
    }

    /**
     * @return Logger
     */
    public function getInstance(): Logger
    {
        return $this;
    }

    public function log(string $level, string|\Stringable $message, array $context = []): void
    {
        if (true === WP_DEBUG) {
            $data = [];
            $data['level'] = $level;
            $data['action'] = $this->_action;
            $data['message'] = $message;

            error_log(print_r($data + $context, true));
        }
    }

    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::LEVEL_ERROR, $message, $context);
    }

    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::LEVEL_INFO, $message, $context);
    }

    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::LEVEL_NOTICE, $message, $context);
    }

    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::LEVEL_DEBUG, $message, $context);
    }

    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::LEVEL_WARNING, $message, $context);
    }

    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::LEVEL_CRITICAL, $message, $context);
    }
}
