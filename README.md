# MageRock
## a Magento, Modman, Composer, Capistrano project
Fork this repo to start a new Magento site with everything in place for quick and easy development.

# Quick Start
### Local Dev Environment
1. Clone the repo
2. Setup your `.env` file appropriately
3. Run `composer update`
4. Run `./scripts/run-modman.sh`
4. Run `./scripts/install-magento.sh` to get a fresh Magento install

### Remote Stage Environment
In the following steps, `<stage>` is the Capistrano stage you want to use. 

1. Set up the Capistrano stage you want to use
2. Run `bin/cap <stage> mage:init`
3. Setup the `.env` file in the deploy directory appropriately
4. Run `bin/cap <stage> deploy:check`
5. Run `bin/cap <stage> deploy` (`mage:modman` and `mage:localxml` are now post-deploy tasks)
6. [Optional] Run `bin/cap <stage> mage:install` if you want a fresh Magento install

# Etc.
### modman
* In Magerock based projects, we can and prefer to keep extensions in their own modman linked directories a la 
```web
├── magento
├── some-extension-here
├── some-other-extension
├── some-theme-overrides
└── theme
```
When running `./scripts/run-modman.sh` for the first time, it will generate modman files for any extensions that don't have them using `modman-gen`. 

* `bin/cap <stage> mage:prodgen` (or `php -f web/magento/shell/generate.php -- -n 50`) generates 50 lorem-ipsum-esque products.
