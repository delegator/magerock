# Magerock

A Magento, Composer, and Capistrano project

Use this project template to start a new Magento CE 1.x site with everything in place for quick and easy development.

# Requirements

 - [PHP][1] `>= 5.4`
 - [Composer][2] `>= 1.0.0`
 - [n98-magerun][3] `>= 1.97.11`
 - [Ruby][4] `>= 1.9.3` with [Bundler][5]

# Getting Started

In your local development environment:

```bash
# Create a new project from this template
$ composer create-project delegator/magerock myproject dev-master --repository-url=https://packages.delegator.com/
# Change this to the name of your project!   ^^^^^^^^^

# Go to your new project
$ cd myproject

# Copy the sample environment file and edit where appropriate.
# If you'd like to use the PHP built-in web server, we don't recommend changing the BASE_URL.
$ cp .env.example .env

# Create the database you specified in your .env file:
$ mysql -e "create database myproject_development"

# Install Magento (wait patiently!)
$ ./scripts/install-magento

# Use PHP's built-in web server to run the site locally.
# Visit http://localhost:8080/ in your favorite browser!
$ php -S 127.0.0.1:8080 -t web router.php
```

# Documentation

Please see https://github.com/delegator/magerock/wiki for complete documentation!

# Project layout

In Magerock-based projects, your project will look roughly like this:

```
├── packages/
├── third-party/
└── web/
```

:exclamation: Wait! Don't copy that extension code just yet! :exclamation:

You might be surprised to find just how many of your favorite Magento modules
are installable directly via composer, without you having to copy a single line
of PHP code into your project! Take a look: https://packages.firegento.com/

### `packages` directory

Are you working on custom modules? Taking ownership of a theme? Overriding some
jank third party code? Your code belongs in the `packages/` directory. It is
suggested that you use the naming scheme `vendor-name/extension-name`.

### `third-party` directory

Purchased extensions and other modules not available in the Firegento repository
should be placed in the `third-party` directory. Use the same naming scheme as
the packages directory above (`third-party/extension-name`).

### `web` directory

This is your web root. Magento core will be installed here. When you run `$
composer install`, Composer will link each module's files into the configured
locations, much like modman.

# Deploying

```bash
# Install dependencies
$ bundle install

# List available capistrano tasks
$ bin/cap -T
```

In the following steps, `<stage>` is the Capistrano stage you want to use.

1. Set up the Capistrano stage you want to use
2. Run `bin/cap <stage> mage:init`
3. Set up your `web/app/etc/local.xml` in the `shared/` directory on the remote server.
4. Run `bin/cap <stage> deploy:check`
5. Run `bin/cap <stage> deploy`

# License

Please see [LICENSE][6] for license information.

[1]: https://secure.php.net/
[2]: https://getcomposer.org/
[3]: http://magerun.net/
[4]: https://www.ruby-lang.org/
[5]: http://bundler.io/
[6]: ./LICENSE
