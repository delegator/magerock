# Magerock

A Magento, Composer, and Capistrano project

Use this project template to start a new Magento CE 1.x site with everything in place for quick and easy development.

# Requirements

 - [PHP][1] `>= 5.4`
 - [Composer][2] `>= 1.0.0-alpha11`
 - [n98-magerun][3] `>= 1.97.11`

# Getting Started

In your local development environment:

```bash
# Create a new project from this template
$ composer create-project delegator/magerock myproject dev-master

# Go to your new project
$ cd myproject

# Install dependencies
$ composer install

# Copy the sample environment file and edit where appropriate.
# You can generate an encryption key by running `$ openssl rand -hex 16`
$ cp .env.example .env

# Generate a local.xml file based on the values in .env
$ ./scripts/generate-localxml

# Install Magento
$ ./scripts/install-magento

# Using your favorite web server, create a virtualhost or server block that points at the web directory.
# Woohoo! You're all done!
```

# Project layout

In Magerock-based projects, your project code is located in two places:

```
├── packages/
└── third-party-extensions/
```

### Packages directory

Are you working on custom modules? Overriding some jank third party code? Your
code belongs in the `packages/` directory. It is suggested that you use the
naming scheme `vendor-name/extension-name`.

### Third party extensions

You'll be surprised to find just how many of your favorite Magento modules are
installable directly via composer, without you having to copy a single line of
PHP code into your project! Take a look: http://packages.firegento.com/

In the event that your extension is not an open-source extension listed in the
Firegento package repository, you should place it in the
`third-party-extensions` directory. Use the same naming scheme as the packages
directory above.

# Deploying

In the following steps, `<stage>` is the Capistrano stage you want to use.

1. Set up the Capistrano stage you want to use
2. Run `bin/cap <stage> mage:init`
3. Set up your `web/app/etc/local.xml` in the `shared/` directory on the remote server.
4. Run `bin/cap <stage> deploy:check`
5. Run `bin/cap <stage> deploy`

[1]: https://secure.php.net/
[2]: https://getcomposer.org/
[3]: http://magerun.net/
