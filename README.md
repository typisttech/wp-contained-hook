# WP Contained Hook

[![Latest Stable Version](https://poser.pugx.org/typisttech/wp-contained-hook/v/stable)](https://packagist.org/packages/typisttech/wp-contained-hook)
[![Total Downloads](https://poser.pugx.org/typisttech/wp-contained-hook/downloads)](https://packagist.org/packages/typisttech/wp-contained-hook)
[![Build Status](https://travis-ci.org/TypistTech/wp-contained-hook.svg?branch=master)](https://travis-ci.org/TypistTech/wp-contained-hook)
[![codecov](https://codecov.io/gh/TypistTech/wp-contained-hook/branch/master/graph/badge.svg)](https://codecov.io/gh/TypistTech/wp-contained-hook)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/TypistTech/wp-contained-hook/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/TypistTech/wp-contained-hook/?branch=master)
[![PHP Versions Tested](http://php-eye.com/badge/typisttech/wp-contained-hook/tested.svg)](https://travis-ci.org/TypistTech/wp-contained-hook)
[![StyleCI](https://styleci.io/repos/86774587/shield?branch=master)](https://styleci.io/repos/86774587)
[![License](https://poser.pugx.org/typisttech/wp-contained-hook/license)](https://packagist.org/packages/typisttech/wp-contained-hook)
[![Donate via PayPal](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.typist.tech/donate/wp-contained-hook/)
[![Hire Typist Tech](https://img.shields.io/badge/Hire-Typist%20Tech-ff69b4.svg)](https://www.typist.tech/contact/)

Lazily instantiate objects from dependency injection container to WordPress hooks (actions and filters).

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->


- [The Goals, or What This Package Does?](#the-goals-or-what-this-package-does)
- [Install](#install)
- [Usage](#usage)
- [API](#api)
  - [Loader](#loader)
    - [Loader::__construct(Container $container)](#loader__constructcontainer-container)
    - [Loader::add(AbstractHook ...$hooks)](#loaderaddabstracthook-hooks)
    - [Loader::run()](#loaderrun)
  - [Action](#action)
    - [Action::__construct(string $hook, string $classIdentifier, string $callbackMethod, int $priority = null, int $acceptedArgs = null)](#action__constructstring-hook-string-classidentifier-string-callbackmethod-int-priority--null-int-acceptedargs--null)
  - [Filter](#filter)
    - [Filter::__construct(string $hook, string $classIdentifier, string $callbackMethod, int $priority = null, int $acceptedArgs = null)](#filter__constructstring-hook-string-classidentifier-string-callbackmethod-int-priority--null-int-acceptedargs--null)
- [Frequently Asked Questions](#frequently-asked-questions)
  - [Do you have an example plugin that use this package?](#do-you-have-an-example-plugin-that-use-this-package)
- [Support!](#support)
  - [Donate via PayPal *](#donate-via-paypal-)
  - [Why don't you hire me?](#why-dont-you-hire-me)
  - [Want to help in other way? Want to be a sponsor?](#want-to-help-in-other-way-want-to-be-a-sponsor)
- [Developing](#developing)
- [Running the Tests](#running-the-tests)
- [Feedback](#feedback)
- [Change log](#change-log)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## The Goals, or What This Package Does?

Lazily instantiate objects from dependency injection container to WordPress hooks (actions and filters).

Using dependency container in WordPress plugins or themes. Dependencies are lazy loaded, not instantiated until the first time they are used. 

## Install

Installation should be done via composer, details of how to install composer can be found at [https://getcomposer.org/](https://getcomposer.org/).

``` bash
$ composer require typisttech/wp-contained-hook --dev
```

## Usage

```php
use League\Container\Container;
use League\Container\ReflectionContainer;
use TypistTech\WPContainedHook\Action;
use TypistTech\WPContainedHook\Filter;
use TypistTech\WPContainedHook\Loader;

$container = new Container;

// Optional container config. 
$container->delegate(new ReflectionContainer); 
$someClass = new SomeClass;
$this->container->add(SomeClass::class, $someClass);

$loader = new Loader($container);

// Action.
$action = new Action(SomeClass::class, 'plugin_loaded', 'doSomething');

// Filter.
$filter = new Filter(SomeClass::class, 'the_content', 'filterSomething');

// Add to loader
$loader->add($action, $filter);

// Add to WordPress
$loader->run();
```

## API

### Loader

Register all actions and filters for the plugin.

Maintain a list of all hooks that are registered throughout the plugin, and register them with the WordPress API. Call the `run` function to execute the list of actions and filters.

#### Loader::__construct(Container $container)

Loader constructor.

* @param League\Container\Container\Container $container The container.

Example:

```php
$container = new Container;
$loader = new Loader($container);
```

#### Loader::add(AbstractHook ...$hooks)

Add new hooks to the collection to be registered with WordPress.

* @param AbstractHook|AbstractHook[] ...$hooks Hooks to be registered.

Example:

```php
// Action.
$action = new Action(SomeClass::class, 'plugin_loaded', 'doSomething');

// Filter.
$filter = new Filter(SomeClass::class, 'the_content', 'filterSomething');

// Add to loader
$loader->add($action, $filter);
```

#### Loader::run()

Register the hooks to the container and WordPress.

Example:

```php
$loader->run();
```

### Action

Holds necessary information for an action.

Subclass of `AbstractHook`.

#### Action::__construct(string $hook, string $classIdentifier, string $callbackMethod, int $priority = null, int $acceptedArgs = null)

Action constructor.

* @param string   $hook            The name of the WordPress hook that is being registered.
* @param string   $classIdentifier Identifier of the entry to look for from container.
* @param string   $callbackMethod  The callback method name.
* @param int|null $priority        Optional.The priority at which the function should be fired. Default is 10.
* @param int|null $acceptedArgs    Optional. The number of arguments that should be passed to the $callback. Default is 1.

### Filter

Holds necessary information for a filter.

Subclass of `AbstractHook`.

#### Filter::__construct(string $hook, string $classIdentifier, string $callbackMethod, int $priority = null, int $acceptedArgs = null)

Filter constructor.

* @param string   $hook            The name of the WordPress hook that is being registered.
* @param string   $classIdentifier Identifier of the entry to look for from container.
* @param string   $callbackMethod  The callback method name.
* @param int|null $priority        Optional.The priority at which the function should be fired. Default is 10.
* @param int|null $acceptedArgs    Optional. The number of arguments that should be passed to the $callback. Default is 1.


## Frequently Asked Questions

### Do you have an example plugin that use this package?

Here you go: 

 * [Sunny](https://github.com/TypistTech/sunny)
 * [WP Cloudflare Guard](https://github.com/TypistTech/wp-cloudflare-guard)

*Add your own plugin [here](https://github.com/TypistTech/wp-contained-hook/edit/master/README.md)*

## Support!

### Donate via PayPal [![Donate via PayPal](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.typist.tech/donate/wp-contained-hook/)

Love WP Contained Hook? Help me maintain WP Contained Hook, a [donation here](https://www.typist.tech/donate/wp-contained-hook/) can help with it. 

### Why don't you hire me?
Ready to take freelance WordPress jobs. Contact me via the contact form [here](https://www.typist.tech/contact/) or, via email info@typist.tech 

### Want to help in other way? Want to be a sponsor? 
Contact: [Tang Rufus](mailto:tangrufus@gmail.com)

## Developing

To setup a developer workable version you should run these commands:

```bash
$ composer create-project --keep-vcs --no-install typisttech/wp-contained-hook:dev-master
$ cd wp-contained-hook
$ composer install
```

## Running the Tests

[WP Contained Hook](https://github.com/TypistTech/wp-contained-hook) run tests on [Codeception](http://codeception.com/).

Run the tests:

``` bash
$ composer test
```

We also test all PHP files against [PSR-2: Coding Style Guide](http://www.php-fig.org/psr/psr-2/) and part of the [WordPress coding standard](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards).

Check the code style with ``$ composer check-style`` and fix it with ``$ composer fix-style``.

## Feedback

**Please provide feedback!** We want to make this library useful in as many projects as possible.
Please submit an [issue](https://github.com/TypistTech/wp-contained-hook/issues/new) and point out what you do and don't like, or fork the project and make suggestions.
**No issue is too small.**

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email wp-contained-hook@typist.tech instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CODE_OF_CONDUCT](.github/CODE_OF_CONDUCT.md) for details.

## Credits

[WP Contained Hook](https://github.com/TypistTech/wp-contained-hook) is a [Typist Tech](https://www.typist.tech) project and maintained by [Tang Rufus](https://twitter.com/Tangrufus), freelance developer for [hire](https://www.typist.tech/contact/).

Full list of contributors can be found [here](https://github.com/TypistTech/wp-contained-hook/graphs/contributors).

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
