# psr7-http PHP docs
PHP usage documentation

----

# Namespaces

## Namespaces

Name      | Interfaces | Classes | Traits 
----------|------------|---------|--------
\Psr7Http | 0          |  4      |  1     

# Classes

## \Psr7Http

Name     | Description    | -    
---------|----------------|-------
Request  | Request class  | -     
Response | Response class | -     
Stream   | Stream class   | -     
Uri      | Uri class      | -     

# Interfaces

# Traits

## \Psr7Http

Name      | Description     
----------|-----------------
BodyTrait | BodyTrait trait 

# Psr7Http\BodyTrait::getBody

#### 

## Signature

`public function getBody() `

## Returns

StreamInterface
    Returns the body as a stream.  

# Psr7Http\BodyTrait

#### BodyTrait trait

  * Author: D. Bird <retran@gmail.com>

## Synopsis

trait BodyTrait {  

  * // members
  * private resource|string $body; 

  * // methods
  * public StreamInterface getBody() 

}  

## Hierarchy

#### Used by

  * Psr7Http\Request
  * Psr7Http\Response

## Members

#### private

  * __$body__ — Psr7Http\resource|string

## Methods

#### public

  * getBody()

# Psr7Http\Uri::withFragment

#### 

## Signature

`public function withFragment( $fragment ) `

## Parameters

`$fragment` — 
    

# Psr7Http\Uri::withUserInfo

#### 

## Signature

`public function withUserInfo( $user, [ $password = NULL] ) `

## Parameters

`$user` — 
    
`$password` — 
    

# Psr7Http\Uri::withPath

#### 

## Signature

`public function withPath( $path ) `

## Parameters

`$path` — 
    

# Psr7Http\Uri::getQuery

#### 

## Signature

`public function getQuery() `

# Psr7Http\Uri::getPort

#### 

## Signature

`public function getPort() `

# Psr7Http\Uri::getPath

#### 

## Signature

`public function getPath() `

# Psr7Http\Uri::withPort

#### 

## Signature

`public function withPort( $port ) `

## Parameters

`$port` — 
    

# Psr7Http\Uri::modifyUri

#### 

## Signature

`protected function modifyUri(string $uri, string $component_name, string $value ) `

## Parameters

`$uri` — string
    
`$component_name` — string
    
`$value` — string
    

# Psr7Http\Uri::getScheme

#### 

## Signature

`public function getScheme() `

# Psr7Http\Uri::__construct

#### 

## Signature

`public function __construct(string $uri ) `

## Parameters

`$uri` — string
    

# Psr7Http\Uri::withQuery

#### 

## Signature

`public function withQuery( $query ) `

## Parameters

`$query` — 
    

# Psr7Http\Uri::__toString

#### 

## Signature

`public function __toString() `

# Psr7Http\Uri::getUserInfo

#### 

## Signature

`public function getUserInfo() `

# Psr7Http\Uri::getFragment

#### 

## Signature

`public function getFragment() `

# Psr7Http\Uri::withHost

#### 

## Signature

`public function withHost( $host ) `

## Parameters

`$host` — 
    

# Psr7Http\Uri::concatUri

#### 

## Signature

`protected function concatUri(array $component ) `

## Parameters

`$component` — array
    

# Psr7Http\Uri::getHost

#### 

## Signature

`public function getHost() `

# Psr7Http\Uri::getAuthority

#### 

## Signature

`public function getAuthority() `

# Psr7Http\Uri::withScheme

#### 

## Signature

`public function withScheme( $scheme ) `

## Parameters

`$scheme` — 
    

# Psr7Http\Response

#### Response class

  * Author: D. Bird <retran@gmail.com>

## Synopsis

class Response implements ResponseInterface {  

  * // constants
  * const REASON_PHRASE = ;

  * // members
  * private $header; 
  * private $protocolVersion; 
  * private $statusCode; 
  * private $reasonPhrase; 

  * // methods
  * protected string getStatusLine() 
  * public void __construct() 
  * public void withStatus() 
  * public bool hasHeader() 
  * public array getHeaders() 
  * public void withProtocolVersion() 
  * public void withoutHeader() 
  * public void getHeaderLine() 
  * public void withHeader() 
  * public void withBody() 
  * public void getReasonPhrase() 
  * public void getHeader() 
  * public void getProtocolVersion() 
  * public int getStatusCode() 
  * public void withAddedHeader() 

  * // Inherited methods from BodyTrait
  * public StreamInterface getBody() 

}  

## Hierarchy

#### Uses

  * Psr7Http\BodyTrait

#### Implements

  * Psr\Http\Message\ResponseInterface

## Constants

Name          | Value 
--------------|-------
REASON_PHRASE | -    

## Members

#### private

  * __$header__ — array
  * __$protocolVersion__
  * __$reasonPhrase__ — string
  * __$statusCode__ — int

## Methods

#### protected

  * getStatusLine()

#### public

  * __construct()
  * getHeader()
  * getHeaderLine()
  * getHeaders()
  * getProtocolVersion()
  * getReasonPhrase()
  * getStatusCode()
  * hasHeader()
  * withAddedHeader()
  * withBody()
  * withHeader()
  * withProtocolVersion()
  * withStatus()
  * withoutHeader()

### Inherited from Psr7Http\BodyTrait

#### public

  * getBody()

# Psr7Http\Request::getUri

#### 

## Signature

`public function getUri() `

# Psr7Http\Request::getHeaderLine

#### 

## Signature

`public function getHeaderLine( $name ) `

## Parameters

`$name` — 
    

# Psr7Http\Request::getProtocolVersion

#### 

## Signature

`public function getProtocolVersion() `

# Psr7Http\Request::withRequestTarget

#### 

## Signature

`public function withRequestTarget( $requestTarget ) `

## Parameters

`$requestTarget` — 
    

# Psr7Http\Request::withoutHeader

#### 

## Signature

`public function withoutHeader( $name ) `

## Parameters

`$name` — 
    

# Psr7Http\Request::hasHeader

#### 

## Signature

`public function hasHeader( $name ) `

## Parameters

`$name` — 
    

# Psr7Http\Request::getMethod

#### 

## Signature

`public function getMethod() `

# Psr7Http\Request::withUri

#### 

## Signature

`public function withUri(UriInterface $uri, [boolean $preserveHost = false] ) `

## Parameters

`$uri` — 
    
`$preserveHost` — boolean
    

# Psr7Http\Request::getHeader

#### 

## Signature

`public function getHeader( $name ) `

## Parameters

`$name` — 
    

# Psr7Http\Request::getRequestTarget

#### 

## Signature

`public function getRequestTarget() `

# Psr7Http\Request::__construct

#### 

## Signature

`public function __construct( $body, array $header, [array $option = NULL] ) `

## Parameters

`$body` — 
    
`$header` — array
    
`$option` — array
    

# Psr7Http\Request::withAddedHeader

#### 

## Signature

`public function withAddedHeader( $name, $value ) `

## Parameters

`$name` — 
    
`$value` — 
    

# Psr7Http\Request::withProtocolVersion

#### 

## Signature

`public function withProtocolVersion( $version ) `

## Parameters

`$version` — 
    

# Psr7Http\Request::getHeaders

#### 

## Signature

`public function getHeaders() `

# Psr7Http\Request::resolveOption

#### 

## Signature

`protected function resolveOption(string $key, [array $option = NULL] ) `

## Parameters

`$key` — string
    
`$option` — array
    

# Psr7Http\Request::withMethod

#### 

## Signature

`public function withMethod( $method ) `

## Parameters

`$method` — 
    

# Psr7Http\Request::withBody

#### 

## Signature

`public function withBody(StreamInterface $body ) `

## Parameters

`$body` — 
    

# Psr7Http\Request::withHeader

#### 

## Signature

`public function withHeader( $name, $value ) `

## Parameters

`$name` — 
    
`$value` — 
    

# Psr7Http\Stream

#### Stream class

  * Author: D. Bird <retran@gmail.com>

## Synopsis

class Stream implements StreamInterface {  

  * // members
  * private $handle; 
  * private $metadata; 
  * private bool $isSeekable; 
  * private bool $isWriteable; 
  * private bool $isReadable; 
  * private static integer $errorReporting = 0; 

  * // methods
  * private static void ErrorReportingOff() 
  * private static void ErrorReportingOn() 
  * private static string ErrorGetLastClear() 
  * public void __construct() 
  * public void getMetadata() 
  * public void isSeekable() 
  * public void read() 
  * public void tell() 
  * public void isWritable() 
  * public void seek() 
  * public void __toString() 
  * public void getSize() 
  * public void rewind() 
  * public resource detach() 
  * public string getContents() 
  * public void close() 
  * public void eof() 
  * public void write() 
  * public void isReadable() 

}  

## Hierarchy

#### Implements

  * Psr\Http\Message\StreamInterface

## Members

#### private

  * __$errorReporting__ — int
  * __$handle__ — resource
  * __$isReadable__ — Psr7Http\bool
  * __$isSeekable__ — Psr7Http\bool
  * __$isWriteable__ — Psr7Http\bool
  * __$metadata__ — array

## Methods

#### private

  * ErrorGetLastClear()
  * ErrorReportingOff()
  * ErrorReportingOn()

#### public

  * __construct()
  * __toString()
  * close()
  * detach()
  * eof()
  * getContents()
  * getMetadata()
  * getSize()
  * isReadable()
  * isSeekable()
  * isWritable()
  * read()
  * rewind()
  * seek()
  * tell()
  * write()

# Psr7Http\Request

#### Request class

  * Author: D. Bird <retran@gmail.com>

## Synopsis

class Request implements RequestInterface {  

  * // members
  * private array $header = ; 
  * private string $method = 'POST'; 
  * private UriInterface $uri; 
  * private $requestTarget; 

  * // methods
  * protected static void resolveOption() 
  * public void __construct() 
  * public void hasHeader() 
  * public void withUri() 
  * public void getHeaders() 
  * public void getRequestTarget() 
  * public void withRequestTarget() 
  * public void withProtocolVersion() 
  * public void getMethod() 
  * public void withoutHeader() 
  * public void getHeaderLine() 
  * public void withHeader() 
  * public void withBody() 
  * public void getHeader() 
  * public void getProtocolVersion() 
  * public void withMethod() 
  * public void withAddedHeader() 
  * public void getUri() 

  * // Inherited methods from BodyTrait
  * public StreamInterface getBody() 

}  

## Hierarchy

#### Uses

  * Psr7Http\BodyTrait

#### Implements

  * Psr\Http\Message\RequestInterface

## Members

#### private

  * __$header__ — array
  * __$method__ — string
  * __$requestTarget__ — string
  * __$uri__ — \Psr\Http\Message\UriInterface

## Methods

#### protected

  * resolveOption()

#### public

  * __construct()
  * getHeader()
  * getHeaderLine()
  * getHeaders()
  * getMethod()
  * getProtocolVersion()
  * getRequestTarget()
  * getUri()
  * hasHeader()
  * withAddedHeader()
  * withBody()
  * withHeader()
  * withMethod()
  * withProtocolVersion()
  * withRequestTarget()
  * withUri()
  * withoutHeader()

### Inherited from Psr7Http\BodyTrait

#### public

  * getBody()

# Psr7Http\Response::getHeaderLine

#### 

## Signature

`public function getHeaderLine( $name ) `

## Parameters

`$name` — 
    

# Psr7Http\Response::getProtocolVersion

#### 

## Signature

`public function getProtocolVersion() `

# Psr7Http\Response::withoutHeader

#### 

## Signature

`public function withoutHeader( $name ) `

## Parameters

`$name` — 
    

# Psr7Http\Response::hasHeader

#### 

## Signature

`public function hasHeader( $name ) `

## Parameters

`$name` — 
    

## Returns

bool
    Returns true if any header names match the given headername using a case-insensitive string comparison. Returns false ifno matching header name is found in the message.  

# Psr7Http\Response::getStatusLine

#### 

## Signature

`protected function getStatusLine([float $protocolVersion = NULL, [int $statusCode = NULL, [string $reasonPhrase = NULL]]] ) `

## Parameters

`$protocolVersion` — float
    
`$statusCode` — int
    
`$reasonPhrase` — string
    

# Psr7Http\Response::getHeader

#### 

## Signature

`public function getHeader( $name ) `

## Parameters

`$name` — 
    

# Psr7Http\Response::__construct

#### 

## Signature

`public function __construct(StreamInterface|resource|string $body, array $header, [string $statusLine = NULL] ) `

## Parameters

`$body` — object
    response body  
  
  

`$header` — array
    response header specification  
  
  

`$statusLine` — string
    optional status line, which is the first line of the HTTP response; if no value provided, the 0'th $header element is used as the status line

## Errors/Exceptions

` RuntimeException `
    invalid header
` RuntimeException `
    invalid status line

# Psr7Http\Response::withStatus

#### 

## Signature

`public function withStatus( $code, [string $reasonPhrase = ''] ) `

## Parameters

`$code` — 
    
`$reasonPhrase` — string
    

## Errors/Exceptions

` InvalidArgumentException `
    invalid code
` InvalidArgumentException `
    invalid reason phrase

# Psr7Http\Response::withAddedHeader

#### 

## Signature

`public function withAddedHeader( $name, $value ) `

## Parameters

`$name` — 
    
`$value` — 
    

# Psr7Http\Response::withProtocolVersion

#### 

## Signature

`public function withProtocolVersion(string|float $version ) `

## Parameters

`$version` — object
      
  
  

## Errors/Exceptions

` InvalidArgumentException `
    invalid version

# Psr7Http\Response::getHeaders

#### 

## Signature

`public function getHeaders() `

## Returns

array
    Returns an associative array of the message's headers. Each key is a header name, and each value is an array of strings for that header.  

# Psr7Http\Response::withBody

#### 

## Signature

`public function withBody(StreamInterface $body ) `

## Parameters

`$body` — 
    

# Psr7Http\Response::getReasonPhrase

#### 

## Signature

`public function getReasonPhrase() `

# Psr7Http\Response::withHeader

#### 

## Signature

`public function withHeader( $name, $value ) `

## Parameters

`$name` — 
    
`$value` — 
    

# Psr7Http\Response::getStatusCode

#### 

## Signature

`public function getStatusCode() `

## Returns

int
    status code

# Psr7Http\Stream::eof

#### 

## Signature

`public function eof() `

# Psr7Http\Stream::getContents

#### 

## Signature

`public function getContents() `

## Returns

string
    

# Psr7Http\Stream::tell

#### 

## Signature

`public function tell() `

# Psr7Http\Stream::ErrorReportingOff

#### 

## Signature

`private function ErrorReportingOff() `

# Psr7Http\Stream::write

#### 

## Signature

`public function write( $string ) `

## Parameters

`$string` — 
    

# Psr7Http\Stream::read

#### 

## Signature

`public function read( $length ) `

## Parameters

`$length` — 
    

# Psr7Http\Stream::ErrorGetLastClear

#### 

## Signature

`private function ErrorGetLastClear([string $fallback = 'unknown error'] ) `

## Parameters

`$fallback` — string
    

# Psr7Http\Stream::isSeekable

#### 

## Signature

`private function isSeekable() `

# Psr7Http\Stream::getMetadata

#### 

## Signature

`public function getMetadata([ $key = NULL] ) `

## Parameters

`$key` — 
    

# Psr7Http\Stream::isReadable

#### 

## Signature

`private function isReadable() `

# Psr7Http\Stream::__construct

#### 

## Signature

`public function __construct(resource $handle, [boolean $is_readable = true, [boolean $is_seekable = true, [boolean $is_writeable = false]]] ) `

## Parameters

`$handle` — resource
    
`$is_readable` — boolean
    
`$is_seekable` — boolean
    
`$is_writeable` — boolean
    

# Psr7Http\Stream::__toString

#### 

## Signature

`public function __toString() `

# Psr7Http\Stream::getSize

#### 

## Signature

`public function getSize() `

# Psr7Http\Stream::detach

#### 

## Signature

`public function detach() `

## Returns

resource
    

# Psr7Http\Stream::ErrorReportingOn

#### 

## Signature

`private function ErrorReportingOn() `

# Psr7Http\Stream::rewind

#### 

## Signature

`public function rewind() `

# Psr7Http\Stream::close

#### 

## Signature

`public function close() `

## Errors/Exceptions

` RuntimeException `
    

# Psr7Http\Stream::seek

#### 

## Signature

`public function seek( $offset, [ $whence = SEEK_SET] ) `

## Parameters

`$offset` — 
    
`$whence` — 
    

# Psr7Http\Stream::isWritable

#### 

## Signature

`public function isWritable() `

# Psr7Http\Uri

#### Uri class

  * Author: D. Bird <retran@gmail.com>

## Synopsis

class Uri implements UriInterface {  

  * // members
  * private $uri; 

  * // methods
  * public void __construct() 
  * protected static string concatUri() 
  * protected static string modifyUri() 
  * public void withScheme() 
  * public void withPath() 
  * public void withQuery() 
  * public void getScheme() 
  * public void withFragment() 
  * public void withHost() 
  * public void getAuthority() 
  * public void withPort() 
  * public void __toString() 
  * public void getPort() 
  * public void withUserInfo() 
  * public void getPath() 
  * public void getFragment() 
  * public void getUserInfo() 
  * public void getHost() 
  * public void getQuery() 

}  

## Hierarchy

#### Implements

  * Psr\Http\Message\UriInterface

## Members

#### private

  * __$uri__ — string

## Methods

#### protected

  * concatUri()
  * modifyUri()

#### public

  * __construct()
  * __toString()
  * getAuthority()
  * getFragment()
  * getHost()
  * getPath()
  * getPort()
  * getQuery()
  * getScheme()
  * getUserInfo()
  * withFragment()
  * withHost()
  * withPath()
  * withPort()
  * withQuery()
  * withScheme()
  * withUserInfo()


----
# Legal
Copyright (c) 2018-2019, Doug Bird. All Rights Reserved.

Psr7-http is copyrighted free software and is distributed under the terms of the MIT license or the GPLv3 license.

This document was automatically generated by the following:
 * Psr7-http - phpdox.sh
 * html2markdown 2018.1.9
 * phpDox 0.11.2 - Copyright (C) 2010 - 2019 by Arne Blankerts and Contributors


