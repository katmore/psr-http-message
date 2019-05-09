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

use Psr\Http\Message\UriInterface;
use InvalidArgumentException;

/**
 * Uri class
 *
 * @author D. Bird <retran@gmail.com>
 */
class Uri implements UriInterface {

   /**
    *
    * @var string
    */
   private $uri;
   public function __construct(string $uri) {
      if (false===($c = parse_url($uri))) {
         throw new InvalidArgumentException("malformed url");
      }
      if (!in_array($c['scheme'], ['http', 'https'], true)) {
         throw new InvalidArgumentException("unsupported url scheme: {$c['scheme']}");
      }
   }
   protected static function concatUri(array $component): string {
      $component = array_filter($component, function ($v) {
            return is_string($v);
         });
      $uri = "";
      if (isset($component['scheme'])) {
         $uri .= $component['scheme'] . '://';
      }
      if (isset($component['host'])) {
         if (isset($component['user'])) {
            $uri .= $component['user'];
            if (isset($component['pass'])) {
               $uri .= ':' . $component['pass'];
            }
            $uri .= "@";
         }
         $uri .= $component['host'];
         if (isset($component['port'])) {
            $uri .= ':' . $component['port'];
         }
      }
      if (isset($component['path'])) {
         if (isset($component['host'])) {
            $uri .= '/' . ltrim($component['path'], '/');
         } else {
            $uri .= $component['path'];
         }
      }
      if (isset($component['query'])) {
         $uri .= '?' . $component['query'];
      }
      if (isset($component['fragment'])) {
         $uri .= '#' . $component['fragment'];
      }
      return $uri;
   }
   protected static function modifyUri(string $uri, string $component_name, string $value): string {
      $uriComponent = parse_url($uri);
      $uriComponent[$component_name] = $value;
      return static::concatUri($uriComponent);
   }
   public function withScheme($scheme) {
      if (!is_string($scheme))
         throw new InvalidArgumentException();
      return new static(static::modifyUri($this->uri, 'scheme', $scheme));
   }
   public function withPath($path) {
      if (!is_string($path))
         throw new InvalidArgumentException();
      return new static(static::modifyUri($this->uri, 'path', $path));
   }
   public function withQuery($query) {
      if (!is_string($query))
         throw new InvalidArgumentException();
      return new static(static::modifyUri($this->uri, 'query', $query));
   }
   public function getScheme() {
      return parse_url($this->uri, PHP_URL_SCHEME);
   }
   public function withFragment($fragment) {
      if (!is_string($fragment))
         throw new InvalidArgumentException();
      return new static(static::modifyUri($this->uri, 'fragment', $fragment));
   }
   public function withHost($host) {
      if (!is_string($host))
         throw new InvalidArgumentException();
      return new static(static::modifyUri($this->uri, 'host', $host));
   }
   public function getAuthority() {
      return parse_url($this->uri, PHP_URL_SCHEME);
   }
   public function withPort($port) {
      if (!is_string($port))
         throw new InvalidArgumentException();
      return new static(static::modifyUri($this->uri, 'port', $port));
   }
   public function __toString() {
      return $this->uri;
   }
   public function getPort() {
      $p = parse_url($this->uri, PHP_URL_PORT);
      $s = $this->getScheme();
      if ($s === 'http' && $p === '80') {
         return null;
      }
      if ($s === 'https' && $p === '443') {
         return null;
      }
      return $p;
   }
   public function withUserInfo($user, $password = null) {
      if (!is_string($user))
         throw new InvalidArgumentException();
      if ($password !== null && !is_string($password))
         throw new InvalidArgumentException();
      $uri = static::modifyUri($this->uri, 'user', $user);
      if ($password !== null) {
         $uri = static::modifyUri($uri, 'password', $password);
      }
      return new static($uri);
   }
   public function getPath() {
      return parse_url($this->uri, PHP_URL_PATH);
   }
   public function getFragment() {
      return parse_url($this->uri, PHP_URL_FRAGMENT);
   }
   public function getUserInfo() {
      if (null === ($u = parse_url($this->uri, PHP_URL_USER))) {
         return null;
      }
      if (null !== ($p = parse_url($this->uri, PHP_URL_PASS))) {
         return "$u:$p";
      }
      return $u;
   }
   public function getHost() {
      return parse_url($this->uri, PHP_URL_HOST);
   }
   public function getQuery() {
      return parse_url($this->uri, PHP_URL_QUERY);
   }















}
