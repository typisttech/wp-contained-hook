# WP Contained Hook

[![Packagist](https://img.shields.io/packagist/v/typisttech/wp-contained-hook.svg?style=flat-square)](https://packagist.org/packages/typisttech/wp-contained-hook)
[![Packagist](https://img.shields.io/packagist/dt/typisttech/wp-contained-hook.svg?style=flat-square)](https://packagist.org/packages/typisttech/wp-contained-hook)
![PHP from Packagist](https://img.shields.io/packagist/php-v/TypistTech/wp-contained-hook?style=flat-square)
[![CircleCI](https://circleci.com/gh/TypistTech/wp-contained-hook.svg?style=svg)](https://circleci.com/gh/TypistTech/wp-contained-hook)
[![codecov](https://codecov.io/gh/TypistTech/wp-contained-hook/branch/master/graph/badge.svg)](https://codecov.io/gh/TypistTech/wp-contained-hook)
[![GitHub](https://img.shields.io/github/license/TypistTech/wp-contained-hook.svg?style=flat-square)](https://github.com/TypistTech/wp-contained-hook/blob/master/LICENSE.md)
[![GitHub Sponsor](https://img.shields.io/badge/Sponsor-GitHub-ea4aaa?style=flat-square&logo=github)](https://github.com/sponsors/TangRufus)
[![Sponsor via PayPal](https://img.shields.io/badge/Sponsor-PayPal-blue.svg?style=flat-square&logo=paypal)](https://typist.tech/donate/wp-contained-hook/)
[![Hire Typist Tech](https://img.shields.io/badge/Hire-Typist%20Tech-ff69b4.svg?style=flat-square)](https://typist.tech/contact/)
[![Twitter Follow @TangRufus](https://img.shields.io/twitter/follow/TangRufus?style=flat-square&color=1da1f2&logo=twitter)](https://twitter.com/tangrufus)

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->


- [Goals](#goals)
- [Installation](#installation)
- [Usage](#usage)
- [API](#api)
  - [TypistTech\WPContainedHook\Loader](#typisttech%5Cwpcontainedhook%5Cloader)
    - [Loader Constructor](#loader-constructor)
    - [Loader::add(HookInterface ...$hooks)](#loaderaddhookinterface-hooks)
    - [Loader::run()](#loaderrun)
  - [Hooks: Action and Filter](#hooks-action-and-filter)
    - [AbstractHook Constructor.](#abstracthook-constructor)
- [FAQs](#faqs)
  - [Will you add support for older PHP versions?](#will-you-add-support-for-older-php-versions)
  - [It looks awesome. Where can I find some more goodies like this?](#it-looks-awesome-where-can-i-find-some-more-goodies-like-this)
  - [Where can I give :star::star::star::star::star: reviews?](#where-can-i-give-starstarstarstarstar-reviews)
- [Sponsoring :heart:](#sponsoring-heart)
  - [GitHub Sponsors Matching Fund](#github-sponsors-matching-fund)
  - [Why don't you hire me?](#why-dont-you-hire-me)
  - [Want to help in other way? Want to be a sponsor?](#want-to-help-in-other-way-want-to-be-a-sponsor)
- [Running the Tests](#running-the-tests)
- [Feedback](#feedback)
- [Change log](#change-log)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Goals

Using [PSR-11 container implementation](https://www.php-fig.org/psr/psr-11/) in WordPress plugins, themes and packages during WordPress action/filter callbacks.

Dependencies are usually lazy loaded(depends on your container implementation), not instantiated until the first time they are used (during WordPress action/filter callbacks).

## Installation

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

## FAQs

### Will you add support for older PHP versions?

Never! This plugin will only work on [actively supported PHP versions](https://secure.php.net/supported-versions.php).

Don't use it on **end of life** or **security fixes only** PHP versions.

### It looks awesome. Where can I find some more goodies like this?

- Articles on Typist Tech's [blog](https://typist.tech)
- More projects on [Typist Tech's GitHub profile](https://github.com/TypisTTech/)
- More plugins on [TangRufus'](https://profiles.wordpress.org/tangrufus/#content-plugins) wp.org profiles
- Stay tuned on [Typist Tech's newsletter](https://typist.tech/go/newsletter)
- Follow [@TangRufus](https://twitter.com/tangrufus) on Twitter
- Hire [Tang Rufus](https://typist.tech/contact) to build your next awesome site

### Where can I give :star::star::star::star::star: reviews?

Thanks! Glad you like it. It's important to let my know somebody is using this project. Since this is not hosted on wordpress.org, please consider:

- tweet something good with mentioning [@TangRufus](https://twitter.com/tangrufus)
- :star: star this [Github repo](https://github.com/typisttech/wp-contained-hook)
- :eyes: [watch](https://github.com/typisttech/wp-contained-hook/subscription) this Github repo
- write blog posts
- submit [pull requests](https://github.com/typisttech/wp-contained-hook)
- [sponsor](https://github.com/sponsors/TangRufus) Tang Rufus to maintain his open source projects
- hire [Tang Rufus](https://typist.tech/contact) to build your next awesome site

## Sponsoring :heart:

Love `WP Contained Hook`? Help me maintain it, a [sponsorship here](https://typist.tech/donation/) can help with it.

### GitHub Sponsors Matching Fund

Do you know [GitHub is going to match your sponsorship](https://help.github.com/en/github/supporting-the-open-source-community-with-github-sponsors/about-github-sponsors#about-the-github-sponsors-matching-fund)?

[Sponsor now via GitHub](https://github.com/sponsors/TangRufus) to double your greatness.

### Why don't you hire me?

Ready to take freelance WordPress jobs. Contact me via the contact form [here](https://typist.tech/contact/) or, via email [info@typist.tech](mailto:info@typist.tech)

### Want to help in other way? Want to be a sponsor?

Contact: [Tang Rufus](mailto:tangrufus@gmail.com)

## Running the Tests

Run the tests:

``` bash
$ composer test
$ composer style:check
```

## Feedback

**Please provide feedback!** We want to make this library useful in as many projects as possible.
Please submit an [issue](https://github.com/TypistTech/wp-contained-hook/issues/new) and point out what you do and don't like, or fork the project and make suggestions.
**No issue is too small.**

## Change log

Please see [CHANGELOG](./CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email [wp-contained-hook@typist.tech](mailto:wp-contained-hook@typist.tech) instead of using the issue tracker.

## Credits

[WP Contained Hook](https://github.com/TypistTech/wp-contained-hook) is a [Typist Tech](https://typist.tech) project and maintained by [Tang Rufus](https://twitter.com/Tangrufus), freelance developer for [hire](https://typist.tech/contact/).

Full list of contributors can be found [here](https://github.com/TypistTech/wp-contained-hook/graphs/contributors).

## License

The MIT License (MIT). Please see [License File](./LICENSE) for more information.
