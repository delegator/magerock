# MageRock
## a Magento, Modman, Composer, Capistrano project
Fork this repo to start a new Magento site with everything in place for quick and easy development.

# Quick Start
1. Clone the repo
2. Setup your `.env` file appropriately
3. Run `composer install`
  a. This should install Magento using information from `.env` if you are missing your local.xml
  b. It will overwrite your local.xml with one generated from information from `.env` if Magento is already installed.
