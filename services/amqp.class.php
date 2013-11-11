<?php namespace amateur\services;

use
exception,
PhpAmqpLib\Message\AMQPMessage as message,
PhpAmqpLib\Connection\AMQPConnection as connection;

class amqp
{

  static $params;

  static $channel;

  static function channel()
  {
    if (isset(self::$channel)) {
      return self::$channel;
    }
    if (empty(self::$params)) {
      throw new exception('Message Queue is not configured.', 503);
    }
    $connection = new connection(
      self::$params['host'], self::$params['port'], self::$params['username'], self::$params['password']
    );
    $channel = $connection->channel();
    register_shutdown_function(function() use($connection, $channel) {
      $channel->close();
      $connection->close();
    });
    return self::$channel = $channel;
  }

  static function push($message, $queue)
  {
    self::queue_declare($queue);
    $msg = new message(json_encode($message), ['content_type' => 'application/json']);
    self::channel()->basic_publish($msg, '', $queue);
  }

  static function publish($queue, $message)
  {
    self::channel()->basic_publish(self::json_message($message), '', $queue);
  }

  static function json_message($message)
  {
    return new message(json_encode($message), ['content_type' => 'application/json']);
  }

  static function queue_declare($queue)
  {
    self::channel()->queue_declare($queue, false, true, false, false);
  }

  static function consume($queue, $callback)
  {
    self::queue_declare($queue);
    $channel = self::channel();
    $channel->basic_qos(0, 10, false);
    $channel->basic_consume($queue, '', false, false, false, false, function($msg) use($callback) {
      $message = json_decode($msg->body, true);
      $channel = $msg->delivery_info['channel'];
      $delivery_tag = $msg->delivery_info['delivery_tag'];
      $ack = function() use($channel, $delivery_tag) {
        return $channel->basic_ack($delivery_tag);
      };
      $nack = function() use($channel, $delivery_tag) {
        return $channel->basic_nack($delivery_tag, false, true);
      };
      $callback($message, $ack, $nack);
    });
    while (count($channel->callbacks)) {
      $channel->wait();
    }
  }

}
