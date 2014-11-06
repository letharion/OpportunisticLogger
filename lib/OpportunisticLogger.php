<?php

namespace Letharion\Logging;

use Psr\Log\LoggerInterface;

use Monolog\Handler\SyslogHandler;
use Monolog\Handler\FingersCrossedHandler;

/**
 * Opportunistic class that logs everything in memory
 */
class OpportunisticLogger implements LoggerInterface {

  protected $logger;
  protected $fxlogger;

  public function __construct($channel, $x = NULL, $facility = 'local0', $level = \Monolog\Logger::NOTICE) {
    $this->logger = new \Monolog\Logger();
    $this->logger->pushHandler(new SyslogHandler($x, 'local0', $level));

    $this->fxlogger = new \Monolog\Logger($channel);
    $this->fxlogger->pushHandler(new FingersCrossedHandler(new SyslogHandler($x)));
  }

  public function debug($message, array $context = Array()) {
    $this->log('debug', $message, $context);
  }
  public function info($message, array $context = Array()) {
    $this->log('info', $message, $context);
  }
  public function notice($message, array $context = Array()) {
    $this->log('notice', $message, $context);
  }
  public function warning($message, array $context = Array()) {
    $this->log('warning', $message, $context);
  }
  public function error($message, array $context = Array()) {
    $this->log('error', $message, $context);
  }
  public function critical($message, array $context = Array()) {
    $this->log('critical', $message, $context);
  }
  public function alert($message, array $context = Array()) {
    $this->log('alert', $message, $context);
  }
  public function emergency($message, array $context = Array()) {
    $this->log('emergency', $message, $context);
  }

  public function log($level, $message, array $context = Array()) {
    $this->logger->$level($message, $context);
    $this->fxlogger->$level($message, $context);
  }
}

