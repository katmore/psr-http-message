<?php

/*
 * This file is part of the psr7-http package.
 *
 * (c) D. Bird <retran@gmail.com>, All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psr7Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use InvalidArgumentException;
use RuntimeException;

/**
 * Request class
 *
 * @author D. Bird <retran@gmail.com>
 */
class Request implements RequestInterface {

   use BodyTrait;

   /**
    *
    * @var string[][]
    */
   private $header = ['content-type'=>['application/json']];

   /**
    *
    * @var string
    */
   private $method = 'POST';
   /**
    *
    * @var \Psr\Http\Message\UriInterface
    */
   private $uri;

   /**
    *
    * @var string
    */
   private $requestTarget;

   protected static function resolveOption(string $key, array $option=null) {
      if ($option===null) return null;
      if (isset($option[$key])) return $option[$key];

   }

   /**
    *
    * @param \Psr\Http\Message\UriInterface $uri            request URI
    * @param string                         $request_target optionally specifify request target, otherwise it is determiend from the request uri
    */
   public function __construct($body, array $header, array $option=null) {
      //$this->body = json_encode($message);
      //$this->uri = $uri;
      $this->body = $body;

      //$this->requestTarget = isset($option['request!==null?$request_target:$this->uri->getPath();


   }

   public function hasHeader($name) {

      $name = strtolower($name);

      return isset($this->header[$name]);

   }

   public function withUri(UriInterface $uri, $preserveHost = false) {
      $request = new static($uri);
      if ($preserveHost===true) {
         if (!$request->hasHeader("host") && $uri->getHost()!==null) {
            $request = $request->withHeader("host", $uri->getHost());
         }
      }
      return $request;
   }

   public function getHeaders() {
      return $this->header;
   }

   public function getRequestTarget() {
      return $this->requestTarget;
   }

   public function withRequestTarget($requestTarget) {
      if (!is_string($requestTarget)) return clone $this;
      return new static($this->uri, $requestTarget);
   }

   public function withProtocolVersion($version) {
   }

   public function getMethod() {
   }

   public function withoutHeader($name) {
   }

   public function getHeaderLine($name) {
   }

   public function withHeader($name, $value) {
   }

   public function withBody(StreamInterface $body) {
   }

   public function getHeader($name) {
   }

   public function getProtocolVersion() {
   }

   public function withMethod($method) {
   }

   public function withAddedHeader($name, $value) {
   }

   public function getUri() {
   }


}
