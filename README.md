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
 * [docs/phpdox.md](./docs/phpdox.md) : phpdox generated documentation
 * [docs/coverage.txt](./docs/coverage.txt): unit test coverage report
 * [docs/testdox.txt](./docs/testdox.txt): unit test agile documentation

## Legal
"psr7-http" is distributed under the terms of the [MIT license](LICENSE) or the [GPLv3](GPLv3) license.

Copyright (c) 2018-2019, Doug Bird.
All rights reserved.
