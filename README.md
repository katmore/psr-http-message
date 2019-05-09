# psr7-http
"bare-bones" PSR-7 compliant HTTP messaging library

## Features
 * **Implementation Neutral**
    > I have attempted to make this useful out-of-the-box, but at the same time, neutral to the actual implementation of executing the HTTP requests and obtaining the responses.

## Q&A

* Why is this important or useful?
  > PSR-7 represents years of collaboration and has emerged the consensus for working with HTTP messages in PHP.
  
  > Implementing PSR-7 will provide your project with the potential for greater flexibility and easier code interoperability.
 
* Why not just use Guzzle?
  > I actually recommend it: https://github.com/guzzle/psr7
  
  > Guzzle offers an ideal path to incorporate PSR-7 compliant messaging for many PHP projects.
  
  > However, this library was created for those who refuse to use Guzzle due to some whim or urge; or simply for those times you want to "roll your own".
  
  > It may also be useful for circumstances when Guzzle may not best suit the needs of your project.
  
## Unit Tests
 * [`coverage.txt`](./coverage.txt): unit test coverage report
 * [`phpunit.xml`](./phpunit.xml): PHPUnit configuration file
 * [`tests/phpunit`](./tests/phpunit): source code for unit tests

To perform unit tests, execute phpunit located in the `vendor/bin` directory.
```sh
vendor/bin/phpunit
```

The [`tests.sh`](./tests.sh) wrapper script is provided for convenience.
```sh
./tests.sh
```

## Legal
"psr-http-message" is distributed under the terms of the [MIT license](LICENSE) or the [GPLv3](GPLv3) license.

Copyright (c) 2018-2019, Doug Bird.
All rights reserved.
