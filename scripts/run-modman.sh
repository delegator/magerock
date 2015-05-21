#!/bin/bash
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

MM=${1:-modman}
MG=${1:-modman-gen}

pushd $DIR/../web/magento >/dev/null

echo "Initializing Modman"
if [ -e ".modman" ]
then
    echo "└─ Modman already initialized"
else
    echo "├─ $($MM init)"
    while read -r dir
    do
        if [ $dir = "." ]
            then
            continue
        fi
        if [ $dir = "./magento" ]
            then
            continue
        fi
        echo "├─ $(echo $dir | sed 's/^..//')"


        pushd "../$dir" >/dev/null
        if [ ! -f modman ]
            then
            echo "│  ├─ Touching modman file"
            touch modman
        fi
        popd >/dev/null

        echo "│  ├─ $($MM link ../$dir)"

        pushd .modman/"$dir" >/dev/null
        if [ ! -s modman ]
            then
            echo "│  ├─ Generating modman file"
            $MG > modman
        fi
        echo "│  └─ Deploying modman"
        $MM deploy >/dev/null
        popd >/dev/null

    done < <(pushd .. >/dev/null && find . -maxdepth 1 -type d && popd >/dev/null)
fi

popd 1>/dev/null
echo "└─ Modman finished"