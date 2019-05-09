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
 * BodyTrait trait
 *
 * @author D. Bird <retran@gmail.com>
 */
trait BodyTrait {

   /**
    *
    * @var resource|string
    */
   private $body;

   /**
    *
    * @return \Psr\Http\Message\StreamInterface Returns the body as a stream.
    */
   public function getBody() {
      if ($this->body instanceof StreamInterface) {
         return $this->body;
      }

      if (is_resource($this->body)) {
         return new Stream($this->body);
      }

      $errorReporting = error_reporting(error_reporting() & ~E_NOTICE & ~E_WARNING);
      $handle = fopen("php://temp", 'r+');
      error_reporting($errorReporting);
      if ($handle === false) {
         $error = error_get_last();
         error_clear_last();
         throw new RuntimeException("fopen() failed: " . !empty($error['message'])?$error['message'] : 'unknown error');
      }

      $errorReporting = error_reporting(error_reporting() & ~E_NOTICE & ~E_WARNING);
      $bytes = fwrite($handle, $this->body);
      error_reporting($errorReporting);
      if ($bytes === false) {
         $error = error_get_last();
         error_clear_last();
         throw new RuntimeException("fwrite() failed: " . !empty($error['message'])?$error['message'] : 'unknown error');
      }

      $errorReporting = error_reporting(error_reporting() & ~E_NOTICE & ~E_WARNING);
      $rewindStatus = rewind($handle);
      error_reporting($errorReporting);
      if ($rewindStatus === false) {
         $error = error_get_last();
         error_clear_last();
         throw new RuntimeException("rewind() failed: " . !empty($error['message'])?$error['message'] : 'unknown error');
      }

      return new Stream($handle);
   }
}
