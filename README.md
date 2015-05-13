# MageRock
## a Magento, Modman, Composer, Capistrano project
Fork this repo to start a new Magento site with everything in place for quick and easy development.

# Quick Start
### Local Dev Environment
1. Clone the repo
2. Setup your `.env` file appropriately
3. Run `composer install`
4. Run `./scripts/run-modman.sh`
4. Run `./scripts/install-magento.sh` to get a fresh Magento install

### Remote Stage Environment
In the following steps, <stage> is one of (production|staging|testing)

1. Run `bin/cap <stage> mage:init`
2. Setup the `.env` file in the deploy directory appropriately
3. Run `bin/cap <stage> deploy:check`
4. Run `bin/cap <stage> deploy`
5. Run `bin/cap <stage> mage:modman`
6. Run `bin/cap <stage> mage:install` if you want a fresh Magento install
