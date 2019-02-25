Charcoal Tinify
===============

[![License][badge-license]][charcoal-contrib-tinify]
[![Latest Stable Version][badge-version]][charcoal-contrib-tinify]
[![Code Quality][badge-scrutinizer]][dev-scrutinizer]
[![Coverage Status][badge-coveralls]][dev-coveralls]
[![Build Status][badge-travis]][dev-travis]

A [Charcoal][charcoal-app] module to add tinify integration to charcoal.



## Table of Contents

-   [Installation](#installation)
    -   [Dependencies](#dependencies)
-   [Configuration](#configuration)
-   [Usage](#usage)
-   [Development](#development)
    -  [API Documentation](#api-documentation)
    -  [Development Dependencies](#development-dependencies)
    -  [Coding Style](#coding-style)
-   [Credits](#credits)
-   [License](#license)



## Installation

The preferred (and only supported) method is with Composer:

```shell
$ composer require locomotivemtl/charcoal-contrib-tinify
```



### Dependencies

#### Required

-   [**PHP 5.6+**](https://php.net): _PHP 7_ is recommended.
-   [**locomotivemtl/charcoal-admin**](https://github.com/locomotivemtl/charcoal-admin) ^0.14.1
-   [**tinify/tinify**](https://github.com/tinify/tinify-php) ^1.5


#### PSR

-   [**PSR-7**][psr-7]: Common interface for HTTP messages. Fulfilled by Slim.
-   [**PSR-11**][psr-11]: Common interface for dependency containers. Fulfilled by Pimple.


## Configuration

In your project's config file, require the tinify module like so : 
```json
{
    "modules": {
        "charcoal/tinify/tinify": {}
    }
}
```

Define an API key, preferably in the admin.json config file since it's use is only required in the cms.
You can generate a key at [https://tinyjpg.com/developers](https://tinyjpg.com/developers)

```json
{
    "apis": {
        "tinify": {
            "key": "3FYkvsXPt7VlZbwHsMnHvmZg2g9jW8dJ"
        }
    }
}
```


## Usage

This contrib adds a menu item to the CMS system menu
![Example](exemple-1.png?raw=true "Example")

**TODO**

-   Add a script to schedule compressions task via cron.


## Development

To install the development environment:

```shell
$ composer install
```

To run the scripts (phplint, phpcs, and phpunit):

```shell
$ composer test
```


### API Documentation

-   The auto-generated `phpDocumentor` API documentation is available at:  
    [https://locomotivemtl.github.io/charcoal-contrib-tinify/docs/master/](https://locomotivemtl.github.io/charcoal-contrib-tinify/docs/master/)
-   The auto-generated `apigen` API documentation is available at:  
    [https://codedoc.pub/locomotivemtl/charcoal-contrib-tinify/master/](https://codedoc.pub/locomotivemtl/charcoal-contrib-tinify/master/index.html)



### Development Dependencies

-   [php-coveralls/php-coveralls][phpcov]
-   [phpunit/phpunit][phpunit]
-   [squizlabs/php_codesniffer][phpcs]



### Coding Style

The charcoal-contrib-tinify module follows the Charcoal coding-style:

-   [_PSR-1_][psr-1]
-   [_PSR-2_][psr-2]
-   [_PSR-4_][psr-4], autoloading is therefore provided by _Composer_.
-   [_phpDocumentor_](http://phpdoc.org/) comments.
-   [phpcs.xml.dist](phpcs.xml.dist) and [.editorconfig](.editorconfig) for coding standards.

> Coding style validation / enforcement can be performed with `composer phpcs`. An auto-fixer is also available with `composer phpcbf`.



## Credits

-   [Joel Alphonso](https://github.com/JoelAlphonso)
-   [Locomotive](https://locomotive.ca/)



## License

Charcoal is licensed under the MIT license. See [LICENSE](LICENSE) for details.



[charcoal-contrib-tinify]:  https://packagist.org/packages/locomotivemtl/charcoal-contrib-tinify
[charcoal-app]:             https://packagist.org/packages/locomotivemtl/charcoal-app

[dev-scrutinizer]:    https://scrutinizer-ci.com/g/locomotivemtl/charcoal-contrib-tinify/
[dev-coveralls]:      https://coveralls.io/r/locomotivemtl/charcoal-contrib-tinify
[dev-travis]:         https://travis-ci.org/locomotivemtl/charcoal-contrib-tinify

[badge-license]:      https://img.shields.io/packagist/l/locomotivemtl/charcoal-contrib-tinify.svg?style=flat-square
[badge-version]:      https://img.shields.io/packagist/v/locomotivemtl/charcoal-contrib-tinify.svg?style=flat-square
[badge-scrutinizer]:  https://img.shields.io/scrutinizer/g/locomotivemtl/charcoal-contrib-tinify.svg?style=flat-square
[badge-coveralls]:    https://img.shields.io/coveralls/locomotivemtl/charcoal-contrib-tinify.svg?style=flat-square
[badge-travis]:       https://img.shields.io/travis/locomotivemtl/charcoal-contrib-tinify.svg?style=flat-square

[psr-1]:  https://www.php-fig.org/psr/psr-1/
[psr-2]:  https://www.php-fig.org/psr/psr-2/
[psr-3]:  https://www.php-fig.org/psr/psr-3/
[psr-4]:  https://www.php-fig.org/psr/psr-4/
[psr-6]:  https://www.php-fig.org/psr/psr-6/
[psr-7]:  https://www.php-fig.org/psr/psr-7/
[psr-11]: https://www.php-fig.org/psr/psr-11/
