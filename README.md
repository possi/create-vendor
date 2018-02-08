create-vendor (composer plugin)
===============================

[composer](https://getcomposer.org) plugin to quickly create new modules within your project.

## What it does / Why do you may need it

With Symonfy 4 it is no longer recommended to split your application code within your project into multiple bundles. You
may either build one large application, or you create multiple composer modules to separate code from your project, that
may later be reused.

While composer makes it super easy to just require a git repository, it is somehow impractical to create such a module
to develop within your project:
* If you just create a folder within vendor/ the module isn't recognized, which means the autoloader and dependencies do not apply
* If you create an empty git project you need to at least commit the composer.json once
* In either case you need to temporary change your projects composer.json to add a repository- or autoload-entry

## Install
```bash
$ # either use it globally
$ composer global require meeva/create-vendor
$ # or just within your current project
$ composer require --dev meeva/create-vendor
```


## Usage
Within your composer-managed project, e.g. a Symfony 4 Application
```bash
$ composer create-vendor yourvendor/newpackage
$ # Interactively fill your first composer.json
```

## How does it work
The `create-vendor` command just chains 2 composer commands for you:
1. chdir to $HOME/.composer/plugin/create-vendor/yourvendor/newpackge and  
    `composer init --name=yourvendor/newpackage`
2. chdir back to your project and  
    `composer require yourvendor/newpackage`

In addition it adds a transparent repository without modifying your composer.json that provides every project from the path
`$HOME/.composer/plugin/create-vendor/*/*` as a path-repository.
This has the same effect as adding the following snippet to your global composer.json:
```json
{
    "repositories": [
        {
            "type": "path",
            "url": "$HOME/.composer/plugin/create-vendor/yourvendor/newpackage"
        }
    ]
}
```
This symlinks the path to your project/vendor-directory. Read https://getcomposer.org/doc/05-repositories.md#path for more info.

You may manually copy any composer module to $HOME/.composer/plugin/create-vendor/*/ to make it available for require without
further changes.