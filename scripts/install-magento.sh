#!/bin/bash
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

P=${1:-php}


pushd $DIR/../web/magento >>/dev/null
tput setaf 3 2>/dev/null
echo "Running \`$P -f install.php -- $($DIR/generate-magento-params.sh)\`"
echo $(tput sgr0 2>/dev/null)
$P -f install.php -- $($DIR/generate-magento-params.sh)
popd >/dev/null
