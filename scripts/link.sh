#!/bin/bash
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

pushd $DIR/../web/magento

tput setaf 4; echo ":: Initializing Modman"
if [ -e ".modman" ]
then
  tput setaf 3; echo "   └─ Modman already initialized"
else
  tput setaf 4; printf "  ├─ "
  modman init
  tput setaf 4; printf "  └─ "
  modman link ../theme
fi

popd 1>/dev/null
tput setaf 4; echo ":: Done"