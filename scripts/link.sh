#!/bin/bash
tput setaf 4; echo ": Starting to link Magento Core"

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

if [ ! -e "$DIR/../web/magento/" ]
then
  mkdir $DIR/../web/magento/
fi

pushd $DIR/../web/magento/ 1>/dev/null

tput setaf 4; echo ": Linking Core"
if ln -s ../../vendor/magento/core/* ./ 2>/dev/null
then
  tput setaf 4; echo "  └─ Core successfully linked"
else
  tput setaf 3; echo "  └─ Core already linked"
fi

tput setaf 4; echo ": Initializing Modman"
if [ -e ".modman" ]
then
  tput setaf 3; echo "  └─ Modman already initialized"
else
  tput setaf 4; printf "  ├─ "
  modman init
  tput setaf 4; printf "  └─ "
  modman link ../theme
fi

popd 1>/dev/null
tput setaf 4; echo ": Done"