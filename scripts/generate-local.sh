#!/bin/bash

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

M=${1:-n98-magerun.phar}

pushd $DIR/../web/magento >>/dev/null

if [ -f app/etc/local.xml ]
then
    mv app/etc/local.xml app/etc/local.backup
    $M local-config:generate \
        "$DB_HOST" \
        "$DB_USER" \
        "$DB_PASSWORD" \
        "$DB_NAME" \
        files \
        admin \
        "$SECRET_KEY" >/dev/null && \
    rm app/etc/local.backup && \
    echo "Success"
fi

echo "Done"

popd >/dev/null
