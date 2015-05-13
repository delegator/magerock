#!/bin/bash

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

pushd $DIR/../web/magento >>/dev/null

if [ -f app/etc/local.xml ]
then
    mv app/etc/local.xml app/etc/local.backup
    n98-magerun.phar local-config:generate \
        "$DB_HOST" \
        "$DB_USER" \
        "$DB_PASSWORD" \
        "$DB_NAME" \
        files \
        admin \
        "$SECRET_KEY" >/dev/null && \
    rm app/etc/local.backup
    tput setaf 2
    echo -n "Success"
else
    tput setaf 1
    echo -n "No local.xml. You should run scripts/install-magento.sh"
fi

echo $(tput sgr0)

popd >/dev/null
