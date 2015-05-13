#!/bin/bash
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

MM=${1:-modman}

pushd $DIR/../web/magento >/dev/null

echo "Initializing Modman"
if [ -e ".modman" ]
then
  echo "└─ Modman already initialized"
else
  echo "├─ $($MM init)"
  echo "└─ $($MM link ../theme)"
fi

popd 1>/dev/null
echo "Modman finished"