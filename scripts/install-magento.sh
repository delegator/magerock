#!/bin/bash
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

P=${1:-php}


pushd $DIR/../web/magento >>/dev/null
tput setaf 3
echo "Running \`$P -f install.php -- $($DIR/generate-magento-params.sh)\`"
echo $(tput sgr0)
$P -f install.php -- $($DIR/generate-magento-params.sh)
popd >/dev/null
