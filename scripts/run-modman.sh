#!/bin/bash
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

MM="$1"
echo "foo"
echo "bar: $MM"

pushd $DIR/../web/magento >/dev/null

echo "Initializing Modman"
if [ -e ".modman" ]
then
  echo "└─ Modman already initialized"
else
  printf "├─ "
  $MM init
  printf "└─ "
  $MM link ../theme
fi

popd 1>/dev/null
echo "Modman finished"