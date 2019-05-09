<?php
declare(strict_types = 1)
   ;
namespace Psr7Http\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Psr7Http\BodyTrait;
use Psr\Http\Message\StreamInterface;

class BodyTraitTest extends TestCase {
   public function bodyStringProvider(): array {
      return [
               ['test request body'],
               ['{"foo":"bar"}'],
      ];
   }
   /**
    * @dataProvider bodyStringProvider
    */
   public function testBodyFromString(string $body) {
      $bodyClass = new class($body) {
         use BodyTrait;
         public function __construct(string $body) {
            $this->body = $body;
         }
      };
      $this->assertInstanceOf('Psr\Http\Message\StreamInterface', $bodyClass->getBody());
      $this->assertEquals($body,$bodyClass->getBody());
   }
}