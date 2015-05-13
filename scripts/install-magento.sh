#!/bin/bash
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

pushd $DIR/../web/magento >>/dev/null
tput setaf 3
echo "Running \`php -f install.php -- $($DIR/generate-magento-params.sh)\`"
echo $(tput sgr0)
php -f install.php -- $($DIR/generate-magento-params.sh)
popd >/dev/null
