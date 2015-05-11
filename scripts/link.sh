DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
mkdir $DIR/../web/magento/
pushd $DIR/../web/magento/

ln -s ../../vendor/magento/core/* ./
modman init
modman link ../theme

popd