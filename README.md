# psr7-http
A [PSR-7](https://www.php-fig.org/psr/psr-7/) compliant HTTP messaging library.

## Features
 * **Implementation Neutral**
    > I have attempted to make this useful out-of-the-box, while being as neutral as possible as to the actual details of executing the HTTP requests and obtaining the responses.

## Q&A

* Why is this important or useful?
  > PSR-7 represents years of collaboration and has emerged the consensus for working with HTTP messages in PHP.
  
  > Implementing PSR-7 will provide your project with the potential for greater flexibility and easier code interoperability.
 
* Why not just use Guzzle (or Symfony, or Laravel, or...)?
  > I actually recommend Guzzle (https://github.com/guzzle/psr7); I am providing this as an alternative for those refuse to use Guzzle due to some whim or urge.
  
  > It may further provide useful for circumstances when Guzzle may not best suit the needs of your project.
  
## Documentation
 * [phpdoc generated documentation](./phpdoc.md)
 * [class disagram](./classes.svg)
  
## Unit Tests
 * [`coverage.txt`](./coverage.txt): unit test coverage report
 * [`phpunit.xml.dist`](./phpunit.xml.dist): PHPUnit configuration file
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
"psr7-http" is distributed under the terms of the [MIT license](LICENSE) or the [GPLv3](GPLv3) license.

Copyright (c) 2018-2019, Doug Bird.
All rights reserved.
