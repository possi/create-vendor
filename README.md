create-vendor (composer plugin)
===============================

[composer](https://getcomposer.org) plugin to quickly create new modules within your project.

## What it does / Why do you may need it

Since Symonfy 4 it is no longer recommended to split your application your project into multiple bundles within your application.
You may either build one complex application, or you swap out reusable bundles into separate composer packages.

While composer makes it simple to require an existing package, it is somehow impractical to create a new one to develop within your project:
* If you just create a folder within vendor/ the package isn't recognized, which means that the autoloader and dependencies do not apply
* If you create an empty git project you need to commit the composer.json at least once (more likely 2, 3 or a dozen times)
* In either case you need to temporarily change your projects composer.json to add a repository-entry which can be removed if you later publish the package

### The Solution

This plugins helps you to create a new composer package directory and correctly require it with just one command. Using
this method your `vendor/yourvendor/newpackage/composer.json` is applied as expected:
* The requirements are added to your project
* The autoloader paths are recognized

## Installation
```bash
$ # either use it globally
$ composer global require meeva/create-vendor
$ # or just within your current project
$ composer require --dev meeva/create-vendor
```

## Usage
Within your composer-managed application-project (e.g. a Symfony 4 Application), just type:
```bash
$ composer create-vendor yourvendor/newpackage
$ # Interactively fill your first composer.json
```

## How does it work
The `create-vendor` command just chains 2 composer commands for you:
1. chdir to $HOME/.composer/plugin/create-vendor/yourvendor/newpackage and  
    `composer init --name=yourvendor/newpackage`
2. chdir back to your project and  
    `composer require yourvendor/newpackage`

In addition it adds a transparent ()repository without modifying your composer.json) that provides every project from the directory
`$HOME/.composer/plugin/create-vendor/*/*` as a path-repository. This has the same effect as adding the following snippet to your global composer.json:
```json
{
    "repositories": [
        {
            "type": "path",
            "url": "$HOME/.composer/plugin/create-vendor/*/*"
        }
    ]
}
```
This symlinks the path to your project/vendor-directory. For more information read: https://getcomposer.org/doc/05-repositories.md#path

Bonus: You may manually copy any composer package to `$HOME/.composer/plugin/create-vendor/*/` to make it available for requiring without
additional changes to any composer.json.

## When you are done

First assure that you have published your new package.

After that you may delete the directory `$HOME/.composer/plugin/create-vendor/yourvendor/newpackage`. From now on `composer install/update`
should load your package from the external repository (e.g. packagist.org) as usual.
