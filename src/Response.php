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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use InvalidArgumentException;
use RuntimeException;

/**
 * Response class
 *
 * @author D. Bird <retran@gmail.com>
 */
class Response implements ResponseInterface {

   use BodyTrait;

   /**
    *
    * @var string[] http reason phrases
    * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
    */
   const REASON_PHRASE = [
      100 => "Continue",
      101 => "Switching Protocols",
      102 => "Processing",
      103 => "Early Hints",
      200 => "OK",
      201 => "Created",
      202 => "Accepted",
      203 => "Non-Authoritative Information",
      204 => "No Content",
      205 => "Reset Content",
      206 => "Partial Content",
      207 => "Multi-Status",
      208 => "Already Reported",
      226 => "IM Used",
      300 => "Multiple Choices",
      301 => "Moved Permanently",
      302 => "Found",
      303 => "See Other",
      304 => "Not Modified",
      305 => "Use Proxy",
      306 => "(Unused)",
      307 => "Temporary Redirect",
      308 => "Permanent Redirect",
      400 => "Bad Request",
      401 => "Unauthorized",
      402 => "Payment Required",
      403 => "Forbidden",
      404 => "Not Found",
      405 => "Method Not Allowed",
      406 => "Not Acceptable",
      407 => "Proxy Authentication Required",
      408 => "Request Timeout",
      409 => "Conflict",
      410 => "Gone",
      411 => "Length Required",
      412 => "Precondition Failed",
      413 => "Request Entity Too Large",
      414 => "Request-URI Too Long",
      415 => "Unsupported Media Type",
      416 => "Requested Range Not Satisfiable",
      417 => "Expectation Failed",
      418 => "I'm a teapot",
      420 => "Enhance Your Calm",
      421 => "Misdirected Request",
      422 => "Unprocessable Entity",
      423 => "Locked",
      424 => "Failed Dependency",
      424 => "Method Failure",
      425 => "Unordered Collection",
      426 => "Upgrade Required",
      428 => "Precondition Required",
      429 => "Too Many Requests",
      431 => "Request Header Fields Too Large",
      450 => "Blocked by Windows Parental Controls",
      451 => "Unavailable For Legal Reasons",
      500 => "Internal Server Error",
      501 => "Not Implemented",
      502 => "Bad Gateway",
      503 => "Service Unavailable",
      504 => "Gateway Timeout",
      505 => "HTTP Version Not Supported",
      506 => "Variant Also Negotiates",
      507 => "Insufficient Storage",
      508 => "Loop Detected",
      510 => "Not Extended",
      511 => "Network Authentication Required"
   ];

   /**
    *
    * @var string[]
    */
   private $header;
   private $protocolVersion;

   /**
    *
    * @var int status code
    */
   private $statusCode;

   /**
    *
    * @var string status reason phrase
    */
   private $reasonPhrase;
   protected function getStatusLine(float $protocolVersion=null, int $statusCode=null, string $reasonPhrase=null): string {

      if ($protocolVersion===null) {
         $protocolVersion = $this->getProtocolVersion();
      }
      if ($statusCode===null) {
         $statusCode = $this->getStatusCode();
      }

      if ($reasonPhrase===null) {
         if (isset(static::REASON_PHRASE[$statusCode])) {
            $reasonPhrase = static::REASON_PHRASE[$statusCode];
         } else if (empty($reasonPhrase = $this->getReasonPhrase())) {
            $reasonPhrase = 'Undefined';
         }
      }
      return "HTTP/$protocolVersion $statusCode $reasonPhrase";
   }

   /**
    *
    * @throws \RuntimeException invalid header
    * @throws \RuntimeException invalid status line
    * @param StreamInterface|resource|string $body       response body
    * @param string[]|string[][]             $header     response header specification
    * @param string                          $statusLine optional status line, which is the first line of the HTTP response; if no value provided, the 0'th $header element is used as the status line
    */
   public function __construct($body, array $header, string $statusLine = null) {
      $this->body = $body;
      if ($statusLine === null) {
         if (!isset($header[0])) {
            throw new RuntimeException("status line is not found in header or status line argument");
         }
         $statusLine = $header[0];
      }
      $statusLine = explode(" ", $statusLine);
      $httpVersion = array_shift($statusLine);
      $httpVersion = explode('/', $httpVersion);
      if (!isset($httpVersion[1])) {
         throw new RuntimeException("invalid status line: missing protocol version");
      }
      if (false === ($this->protocolVersion = filter_var($httpVersion[1], FILTER_VALIDATE_FLOAT))) {
         throw new RuntimeException("invalid status line: protocol version not a float value");
      }
      if (!count($statusLine)) {
         throw new RuntimeException("invalid status line: missing status code");
      }
      $this->statusCode = array_shift($statusLine);
      if (false === ($this->statusCode = filter_var($this->statusCode, FILTER_VALIDATE_INT))) {
         throw new RuntimeException("invalid status line: status code not an int value");
      }
      if (empty($this->reasonPhrase = implode(" ", $statusLine))) {
         throw new RuntimeException("invalid status line: missing reason code");
      }

      array_walk($header, function ($v, $k) {
            if (is_array($v)) {
               array_walk($v, function ($vv, $vk) use (&$k) {
                     if (!is_string($vv)) {
                        throw new RuntimeException("header '$k:$vk' is invalid: expected a string value, instead got '" . gettype($vv) . "'");
                     }
                  });
            } else if (!is_string($v)) {
               throw new RuntimeException("header '$k' is invalid: expected a string value, instead got '" . gettype($v) . "'");
            }
            if (is_int($k)) {
               $h = explode(":", $v);
               $k = $h[0];
               $v = isset($h[1])?$h[1] : '';
            }
            $k = strtolower($k);
            if (!isset($this->header[$k]))
            $this->header[$k] = [];
            $this->header[$k][] = $v;
         });
   }
   /**
    *
    * @throws \InvalidArgumentException invalid code
    * @throws \InvalidArgumentException invalid reason phrase
    */
   public function withStatus($code, $reasonPhrase = '') {
      if (false===($code=filter_var($code, FILTER_VALIDATE_INT))) {
         throw new InvalidArgumentException("code must be an integer");
      }
      if (empty($reasonPhrase)) {
         $reasonPhrase = null;
      } else if (!is_string($reasonPhrase)) {
         throw new InvalidArgumentException("reason phrase must be a string");
      }
      return new static($this->body, $this->header, $this->getStatusLine(null, $code, $reasonPhrase));

   }
   /**
    *
    * @return bool Returns true if any header names match the given headername using a case-insensitive string comparison. Returns false ifno matching header name is found in the message.
    */
   public function hasHeader($name) {
      $name = strtolower($name);
      return isset($this->header[$name]);
   }
   /**
    *
    * @return string[][] Returns an associative array of the message's headers. Each key is a header name, and each value is an array of strings for that header.
    */
   public function getHeaders() {
      return $this->header;
   }

   /**
    *
    * @throws \InvalidArgumentException invalid version
    * @param string|float $version
    */
   public function withProtocolVersion($version) {
      if (false === ($version = filter_var($version, FILTER_VALIDATE_FLOAT))) {
         throw new InvalidArgumentException("version must be a float");
      }

      return new static($this->body, $this->header, $this->getStatusLine($version));
   }
   public function withoutHeader($name) {
      $name = strtolower($name);
      $header = $this->header;
      if (is_string($name) && isset($header[$name]))
         unset($header[$name]);
      return new static($this->body, $header);
   }
   public function getHeaderLine($name) {
      $name = strtolower($name);
      if (!isset($this->header[$name]))
         return "";
      return implode(",", $this->header[$name]);
   }
   public function withHeader($name, $value) {
      $name = strtolower($name);
      if (!is_array($value)) {
         if (!is_string($value)) {
            throw new InvalidArgumentException("invalid header value: must be string or array of strings");
         }
         $value = [
            $value
         ];
      }
      array_walk($value, function ($v, $k) {
            if (!is_string($v)) {
               throw new InvalidArgumentException("invalid header value on element '$k': must be a string, instead got '" . gettype($v) . "'");
            }
         });

      $header = $this->header;

      $header[$name] = $value;

      return new static($this->body, $header, $this->getStatusLine());
   }
   public function withBody(StreamInterface $body) {

      return new static($body, $this->header, $this->getStatusLine());

   }
   public function getReasonPhrase() {
      if (!emtpy($this->reasonPhrase)) {
         return $this->reasonPhrase;
      }
      if (isset(static::REASON_PHRASE[$this->statusCode])) {
         return static::REASON_PHRASE[$this->statusCode];
      }
      return "";
   }
   public function getHeader($name) {
      $name = strtolower($name);
      if (!isset($this->header[$name]))
         return [];
   }
   public function getProtocolVersion() {
      if (empty($this->protocolVersion) || !is_float($this->protocolVersion))
         return "1.0";
      return (string) $this->protocolVersion;
   }
   /**
    *
    * @return int status code
    */
   public function getStatusCode() {
      if (empty($this->statusCode) || !is_int($this->statusCode))
         return 200;
      return $this->statusCode;
   }
   public function withAddedHeader($name, $value) {
      $name = strtolower($name);
      if (!is_array($value)) {
         if (!is_string($value)) {
            throw new InvalidArgumentException("invalid header value: must be string or array of strings");
         }
         $value = [
            $value
         ];
      }

      $header = $this->header;
      if (!isset($header[$name])) $header[$name] = [];

      array_walk($value, function ($v, $k) use(&$header, $name) {
            if (!is_string($v)) {
               throw new InvalidArgumentException("invalid header value on element '$k': must be a string, instead got '" . gettype($v) . "'");
            }
            $header[$name] []= $v;
         });

      return new static($this->body, $header, $this->getStatusLine());

   }
}
