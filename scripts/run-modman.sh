#!/bin/bash
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

pushd $DIR/../web/magento >/dev/null

echo "Initializing Modman"
if [ -e ".modman" ]
then
  echo "└─ Modman already initialized"
else
  printf "├─ "
  modman init
  printf "└─ "
  modman link ../theme
fi

popd 1>/dev/null
echo "Modman finished"