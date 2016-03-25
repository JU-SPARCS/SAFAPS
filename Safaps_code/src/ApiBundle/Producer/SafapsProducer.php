<?php

namespace ApiBundle\Producer;

class SafapsProducer {

      private $producer;

      public function __construct($producer) {
      	     $this->producer = $producer;
      }

      public function publish($message) {
      	     $this->producer->publish(serialize($message));
      }

}