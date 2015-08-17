#!/bin/bash

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

M=${1:-n98-magerun.phar}

source $DIR/../.env

pushd $DIR/../web >>/dev/null

if [ -e app/etc/local.xml ]
then
    mv app/etc/local.xml app/etc/local.backup
fi

$M local-config:generate \
"$DB_HOST" \
"$DB_USER" \
"$DB_PASSWORD" \
"$DB_NAME" \
files \
admin \
"$SECRET_KEY" >/dev/null

echo "Success"

if [ ! -e app/etc/local.backup ]
then
    rm app/etc/local.backup
fi

echo "Done"

popd >/dev/null
