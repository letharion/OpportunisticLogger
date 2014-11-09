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
  protected $messages;

  public function __construct($channel, $messages, $handler) {
    $this->messages = $messages;

    $this->logger = new \Monolog\Logger($channel);
    $this->logger->pushHandler($handler);

    $this->fxlogger = new \Monolog\Logger($channel);
    $this->fxlogger->pushHandler(new FingersCrossedHandler($handler));
  }

  public function debug($message, array $context = array()) {
    $this->log('debug', $message, $context);
  }
  public function info($message, array $context = array()) {
    $this->log('info', $message, $context);
  }
  public function notice($message, array $context = array()) {
    $this->log('notice', $message, $context);
  }
  public function warning($message, array $context = array()) {
    $this->log('warning', $message, $context);
  }
  public function error($message, array $context = array()) {
    $this->log('error', $message, $context);
  }
  public function critical($message, array $context = array()) {
    $this->log('critical', $message, $context);
  }
  public function alert($message, array $context = array()) {
    $this->log('alert', $message, $context);
  }
  public function emergency($message, array $context = array()) {
    $this->log('emergency', $message, $context);
  }

  public function log($level, $message, array $context = array()) {
    $message_data = $this->messages[$message];
    $message = str_replace(
      array('%uuid', '%message'),
      $message_data,
      '(%uuid): %message'
    );

    $this->logger->$level($message, $context);
    $this->fxlogger->$level($message, $context);
  }
}
