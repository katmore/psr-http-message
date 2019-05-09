<?php
namespace Psr7Http;

use Psr\Http\Message\StreamInterface;
use InvalidArgumentException;
use RuntimeException ;

class Stream implements StreamInterface {
   /**
    * @var resource
    */
   private $handle;
   
   /**
    * @var array
    */
   private $metadata;
   
   /**
    * @var bool
    */
   private $isSeekable;
   
   /**
    * @var bool
    */
   private $isWriteable;
   
   /**
    * @var bool
    */
   private $isReadable;
   
   /**
    * @var int
    */
   private static $errorReporting = 0;
   private static function ErrorReportingOff() :void {
      static::$errorReporting = error_reporting(error_reporting() & ~E_NOTICE & ~E_WARNING);
   }
   private static function ErrorReportingOn() : void {
      error_reporting(static::$errorReporting);
   }
   private static function ErrorGetLastClear(string $fallback='unknown error') : string {
      $error = error_get_last();
      error_clear_last();
      return isset($error['message'])?$error['message']:$fallback;
   }
   
   /**
    * @param resource $handle
    */
   public function __construct($handle, bool $is_readable=true,bool $is_seekable=true, bool $is_writeable=false) {
      if (!is_resource($handle)) {
         throw new InvalidArgumentException("handle must be resource, instead got: ".gettype($handle));
      }
      $this->handle = $handle;
      $this->isReadable = $is_readable;
      $this->isSeekable = $is_seekable;
      $this->isWriteable = $is_writeable;
   }
   
   public function getMetadata($key = null) {
      if ($this->metadata===null) {
         $this->metadata = stream_get_meta_data($this->handle);
      }
      if ($key===null) return $this->metadata;
      if (isset($this->metadata[$key])) {
         return $this->metadata[$key];
      }
      return null;
   }

   public function isSeekable() {
      return $this->isSeekable;
   }

   public function read($length) {
      if (feof($this->handle)) return "";
      static::ErrorReportingOff();
      $data = fread($this->handle, $length);
      static::ErrorReportingOn();
      if (false === $data) {
         throw new RuntimeException("fread() failed: ".static::ErrorGetLastClear());
      }
      return $data;
   }

   public function tell() {
      static::ErrorReportingOff();
      $pos = ftell($this->handle);
      static::ErrorReportingOn();
      if (false === $pos) {
         throw new RuntimeException("ftell() failed: ".static::ErrorGetLastClear());
      }
      return $pos;
   }

   public function isWritable() {
      return $this->isWriteable;
   }

   public function seek($offset, $whence = SEEK_SET) {
      if (!$this->isSeekable) throw new RuntimeException("stream not seekable");
      static::ErrorReportingOff();
      $status = fseek($this->handle, $offset, $whence);
      static::ErrorReportingOn();
      if (-1===$status) {
         throw new RuntimeException("fseek() failed: ".static::ErrorGetLastClear());
      }
   }

   public function __toString() {
      $this->rewind();
      return $this->getContents();
   }

   public function getSize() {
      static::ErrorReportingOff();
      $stat = fstat($this->handle);
      static::ErrorReportingOn();
      if (is_array($stat) && isset($stat['size']) && is_int($stat['size'])) {
         return $stat['size'];
      }
      return null;
   }

   public function rewind() {
      static::ErrorReportingOff();
      $status = rewind($this->handle);
      static::ErrorReportingOn();
      if (false === $status) {
         throw new RuntimeException("rewind() failed: ".static::ErrorGetLastClear());
      }
   }

   /**
    * @return resource
    */
   public function detach() {
      $handle = $this->handle;
      $this->handle = null;
      return $handle;
   }

   /**
    * @return string
    */
   public function getContents() {
      static::ErrorReportingOff();
      $contents = stream_get_contents($this->handle);
      static::ErrorReportingOn();
      if (false === $contents) {
         throw new RuntimeException("stream_get_contents() failed: ".static::ErrorGetLastClear());
      }
      return $contents;
   }

   /**
    * @throws \RuntimeException
    */
   public function close() {
      static::ErrorReportingOff();
      $closeStatus = fclose($this->handle);
      static::ErrorReportingOn();
      if (false === $closeStatus) {
         throw new RuntimeException("fclose() failed: ".static::ErrorGetLastClear());
      }
   }

   public function eof() {
      return feof($this->handle);
   }

   public function write($string) {
      if (!$this->isWriteable) throw new RuntimeException("stream not writeable");
      static::ErrorReportingOff();
      $bytes = fwrite($this->handle, $string);
      static::ErrorReportingOn();
      if (false === $bytes) {
         throw new RuntimeException("fwrite() failed: ".static::ErrorGetLastClear());
      }
      return $bytes;
   }

   public function isReadable() {
      return $this->isReadable;
   }

   
}