# MageRock
## a Magento, Modman, Composer, Capistrano project
Fork this repo to start a new Magento site with everything in place for quick
and easy development.

# Quick Start
### Local Dev Environment
1. Clone the repo
2. Copy `.env.example` to `.env` and edit your `.env` file appropriately
3. Run `composer update`
4. Run `./scripts/run-modman`
4. Run `./scripts/install-magento.sh` to get a fresh Magento install

### Remote Stage Environment
In the following steps, `<stage>` is the Capistrano stage you want to use.

1. Set up the Capistrano stage you want to use
2. Run `bin/cap <stage> mage:init`
3. Setup the `.env` file in the deploy directory appropriately
4. Run `bin/cap <stage> deploy:check`
5. Run `bin/cap <stage> deploy` (`mage:modman` and `mage:localxml` are now
  post-deploy tasks)
6. [Optional] Run `bin/cap <stage> mage:install` if you want a fresh Magento
  install

# Hacking
### modman
* In Magerock based projects, all of your code is kept in `src/`
```src
├── app/
├── lib/
├── robots.txt
└── modman
```
* Edit your `modman` file as you add code. The
[modman tutorial](https://github.com/colinmollenhour/modman/wiki/Tutorial)
explains the basic concept.
* To re-deploy `modman`, run `./scripts/run-modman` again in your project root.
* `bin/cap <stage> mage:prodgen` (or
  `php -f web/magento/shell/generate.php -- -n 50`) generates 50
  lorem-ipsum-esque products.
