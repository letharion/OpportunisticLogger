<?php

namespace Letharion\Logging;

use Psr\Log\LoggerInterface;

use Monolog\Logger;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\FingersCrossedHandler;

/**
 * Opportunistic class that logs everything in memory
 *
 * @param string $channel
 *   The loggers channel. See monolog.
 * @param array $messages
 *   Array of messages and their UUIDs.
 * @param object $handler
 *   A monolog handler. Defaults to a Syslog handler if left out.
 */
class OpportunisticLogger implements LoggerInterface {

  protected $logger;
  protected $fxlogger;
  protected $messages;
  protected $instance_hash;

  public function __construct($channel, $messages, $handler = NULL) {
    $this->messages = $messages;

    if ($handler === NULL) {
      $handler = $this->getDefaultHandler();
    }

    $this->logger = new Logger($channel);
    $this->logger->pushHandler($handler);

    $this->fxlogger = new Logger($channel);
    $this->fxlogger->pushHandler(new FingersCrossedHandler($handler));

    $this->instance_hash = md5(uniqid());
  }

  protected function getDefaultHandler() {
    return new SyslogHandler('default-logger', 'local0', Logger::INFO);
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

  public function processMessage($message) {
    $message_data = isset($this->messages[$message]) ? $this->messages[$message] : $message;
    $message = str_replace(
      array('%uuid', '%message'),
      $message_data,
      '(%uuid): %message'
    );
    return 'INSTANCE HASH: ' . $this->instance_hash . ' ' . $message;
  }

  public function log($level, $message, array $context = array()) {

    $message = $this->processMessage($message);
    $this->logger->$level($message, $context);
    $this->fxlogger->$level($message, $context);
  }
}
