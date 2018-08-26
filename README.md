# WP Contained Hook

[![Latest Stable Version](https://poser.pugx.org/typisttech/wp-contained-hook/v/stable)](https://packagist.org/packages/typisttech/wp-contained-hook)
[![Total Downloads](https://poser.pugx.org/typisttech/wp-contained-hook/downloads)](https://packagist.org/packages/typisttech/wp-contained-hook)
[![Build Status](https://travis-ci.org/TypistTech/wp-contained-hook.svg?branch=master)](https://travis-ci.org/TypistTech/wp-contained-hook)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/typisttech/wp-contained-hook.svg)](https://packagist.org/packages/typisttech/wp-contained-hook)
[![codecov](https://codecov.io/gh/TypistTech/wp-contained-hook/branch/master/graph/badge.svg)](https://codecov.io/gh/TypistTech/wp-contained-hook)
[![StyleCI](https://styleci.io/repos/86774587/shield?branch=master)](https://styleci.io/repos/86774587)
[![License](https://poser.pugx.org/typisttech/wp-contained-hook/license)](https://packagist.org/packages/typisttech/wp-contained-hook)
[![Donate via PayPal](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://typist.tech/donate/wp-contained-hook/)
[![Hire Typist Tech](https://img.shields.io/badge/Hire-Typist%20Tech-ff69b4.svg)](https://typist.tech/contact/)

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->


- [The Goals, or What This Package Does?](#the-goals-or-what-this-package-does)
- [Install](#install)
- [Usage](#usage)
- [API](#api)
  - [TypistTech\WPContainedHook\Loader](#typisttech%5Cwpcontainedhook%5Cloader)
    - [Loader Constructor](#loader-constructor)
    - [Loader::add(HookInterface ...$hooks)](#loaderaddhookinterface-hooks)
    - [Loader::run()](#loaderrun)
  - [Hooks: Action and Filter](#hooks-action-and-filter)
    - [AbstractHook Constructor.](#abstracthook-constructor)
- [Frequently Asked Questions](#frequently-asked-questions)
  - [Do you have an example plugin that use this package?](#do-you-have-an-example-plugin-that-use-this-package)
  - [It looks awesome. Where can I find some more goodies like this?](#it-looks-awesome-where-can-i-find-some-more-goodies-like-this)
  - [This plugin isn't on wp.org. Where can I give a :star::star::star::star::star: review?](#this-plugin-isnt-on-wporg-where-can-i-give-a-starstarstarstarstar-review)
  - [This plugin isn't on wp.org. Where can I make a complaint?](#this-plugin-isnt-on-wporg-where-can-i-make-a-complaint)
- [Support!](#support)
  - [Donate via PayPal *](#donate-via-paypal-)
  - [Why don't you hire me?](#why-dont-you-hire-me)
  - [Want to help in other way? Want to be a sponsor?](#want-to-help-in-other-way-want-to-be-a-sponsor)
- [Running the Tests](#running-the-tests)
- [Feedback](#feedback)
- [Change log](#change-log)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## The Goals, or What This Package Does?

Using [PSR-11 container implementation](https://www.php-fig.org/psr/psr-11/) in WordPress plugins, themes and packages during WordPress action/filter callbacks.

Dependencies are usually lazy loaded(depends on your container implementation), not instantiated until the first time they are used (during WordPress action/filter callbacks).

## Install

Installation should be done via composer, details of how to install composer can be found at [https://getcomposer.org/](https://getcomposer.org/).

You need a [`psr/container-implementation` package](https://packagist.org/providers/psr/container-implementation) as well. This readme uses `league/container` as an example (any `psr/container-implementation` works similarly).

``` bash
# league/container is an example, any psr/container-implementation package works
$ composer require typisttech/wp-contained-hook league/container
```

## Usage

```php
use League\Container\Container;
use TypistTech\WPContainedHook\Hooks\Action;
use TypistTech\WPContainedHook\Hooks\Filter;
use TypistTech\WPContainedHook\Loader;

$container = new Container;

// Configure the container.
// This depends on your `psr/container-implementation`.
$container->add('bar', Bar::class);
$container->add('foo', Foo::class);

// Action.
$action = new Action('bar', 'admin_init', 'doSomething');

// Filter.
$filter = new Filter('foo', 'the_content', 'filterSomething');

// Add to loader.
$loader = new Loader($container);
$loader->add($action, $filter);

// Add to WordPress.
$loader->run();
```

In plain WordPress, the above is similar to:

```php
$bar = new Bar();
add_action('admin_init', [$bar, 'doSomething'])

$foo = new Foo();
add_filter('the_content', [$foo, 'filterSomething'])
```

In WordPress plus container, the above is similar to:

```php
add_action('admin_init', function ($arg) use ($container): void {
  $bar = $container->get('bar');
  $bar->doSomething($arg);
})

add_filter('the_content', function ($arg) use ($container) {
  $foo = $container->get('foo');
  return $foo->filterSomething($arg);
})
```

## API

### TypistTech\WPContainedHook\Loader

Register all actions and filters for the plugin/package/theme.

Maintain a list of all hooks that are registered throughout the plugin, and register them with the WordPress API. Call the run function to execute the list of actions and filters.

#### Loader Constructor

* @param Psr\Container\ContainerInterface $container The container.

Example:

```php
$container = new Container;
$loader = new Loader($container);
```

#### Loader::add(HookInterface ...$hooks)

Add new hooks to the collection to be registered with WordPress.

* @param HookInterface|HookInterface[] ...$hooks Hooks to be registered.

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

### Hooks: Action and Filter

Holds necessary information for an action or a filter.

Both `Action` and `Filter` are subclasses of `AbstractHook` and implements `HookInterface`.

#### AbstractHook Constructor.

* @param string   $hook            The name of the WordPress hook that is being registered.
* @param string   $classIdentifier Identifier of the entry to look for from container.
* @param string   $callbackMethod  The callback method name.
* @param int|null $priority        Optional.The priority at which the function should be fired. Default is 10.
* @param int|null $acceptedArgs    Optional. The number of arguments that should be passed to the $callback. Default is 1.

Example:

```php
$action = new Action('bar', 'admin_init', 'doSomething', 20, 2);

$filter = new Filter('foo', 'the_content', 'filterSomething', 20, 2);
```

## Frequently Asked Questions

### Do you have an example plugin that use this package?

Here you go:

 * [Sunny](https://github.com/TypistTech/sunny)
 * [WP Cloudflare Guard](https://github.com/TypistTech/wp-cloudflare-guard)
 * [Disallow Pwned Passwords](https://github.com/ItinerisLtd/disallow-pwned-passwords)

*Add your own plugin [here](https://github.com/TypistTech/wp-contained-hook/edit/master/README.md)*

### It looks awesome. Where can I find some more goodies like this?

* Articles on Typist Tech's [blog](https://typist.tech)
* [Tang Rufus' WordPress plugins](https://profiles.wordpress.org/tangrufus#content-plugins) on wp.org
* More projects on [Typist Tech's GitHub profile](https://github.com/TypistTech)
* Stay tuned on [Typist Tech's newsletter](https://typist.tech/go/newsletter)
* Follow [Tang Rufus' Twitter account](https://twitter.com/TangRufus)
* Hire [Tang Rufus](https://typist.tech/contact) to build your next awesome site

### This plugin isn't on wp.org. Where can I give a :star::star::star::star::star: review?

Thanks!

Consider writing a blog post, submitting pull requests, [donating](https://typist.tech/donation/) or [hiring me](https://typist.tech/contact/) instead.

### This plugin isn't on wp.org. Where can I make a complaint?

To be honest, I don't care.

If you really want to share your 1-star review, send me an email - in the first paragraph, state why didn't invest your time reading the [source code](./src) and making pull requests.

## Support!

### Donate via PayPal [![Donate via PayPal](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://typist.tech/donate/wp-conatined-hook/)

Love WP Contained Hook? Help me maintain WP Contained Hook, a [donation here](https://typist.tech/donate/wp-conatined-hook/) can help with it.

### Why don't you hire me?

Ready to take freelance WordPress jobs. Contact me via the contact form [here](https://typist.tech/contact/) or, via email [info@typist.tech](mailto:info@typist.tech)

### Want to help in other way? Want to be a sponsor?

Contact: [Tang Rufus](mailto:tangrufus@gmail.com)

## Running the Tests

Run the tests:

``` bash
$ composer test
$ composer check-style
```

## Feedback

**Please provide feedback!** We want to make this library useful in as many projects as possible.
Please submit an [issue](https://github.com/TypistTech/wp-contained-hook/issues/new) and point out what you do and don't like, or fork the project and make suggestions.
**No issue is too small.**

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email wp-contained-hook@typist.tech instead of using the issue tracker.

## Credits

[WP Contained Hook](https://github.com/TypistTech/wp-contained-hook) is a [Typist Tech](https://typist.tech) project and maintained by [Tang Rufus](https://twitter.com/Tangrufus), freelance developer for [hire](https://typist.tech/contact/).

Full list of contributors can be found [here](https://github.com/TypistTech/wp-contained-hook/graphs/contributors).

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
