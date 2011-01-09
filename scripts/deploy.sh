#!/bin/bash -
#
# deploy.sh
#
# Description: Unpackages app from tarball to correct directory

DATE=`date`
ROOT_DIR=`dirname $0`
STAGE=$ROOT_DIR/../stage
ALPHA="alpha"
PRD="prd"
USERNAME=`whoami`
HOSTNAME=`hostname`

PUBLIC_ROOT=$ROOT_DIR/../public_html
ALPHA_ROOT=$PUBLIC_ROOT/alpha
PRD_APP_ROOT=$ROOT_DIR/..

usage() {
    echo ""
    echo "Usage: `basename $0` -t [alpha|prd] -f [build-package.tgz]"
    echo ""
}


deploy() {
    echo ""
    echo "----> $USERNAME@$HOSTNAME - DEPLOY"
    echo "  Starting Deploy $DATE"

    TARGET_ENV=${1}
    PACKAGE_NAME=${2}
    PACKAGE=${STAGE}/${PACKAGE_NAME}

    if [ $TARGET_ENV = ${ALPHA} ]; then
        SD_ROOT=$ALPHA_ROOT/studydeck

        echo "  Cleaning $SD_ROOT"
        rm -rf $SD_ROOT

        echo "  Deploying $PACKAGE to $ALPHA_ROOT"
        tar xvzf $PACKAGE -C $ALPHA_ROOT

    elif [ $TARGET_ENV = ${PRD} ]; then
        SD_ROOT=$PRD_APP_ROOT/studydeck

        echo "  Cleaning $SD_ROOT"
        rm -rf $SD_ROOT

        echo "  Deploying $PACKAGE to $PRD_APP_ROOT"
        tar xvzf $PACKAGE -C $PRD_APP_ROOT/

        echo " Cleaning public web root"
        rm -rf $PUBLIC_ROOT/certs
        rm -rf $PUBLIC_ROOT/css
        rm -rf $PUBLIC_ROOT/files
        rm -rf $PUBLIC_ROOT/img
        rm -rf $PUBLIC_ROOT/js
        rm -f $PUBLIC_ROOT/css.php
        rm -f $PUBLIC_ROOT/favicon.ico
        rm -f $PUBLIC_ROOT/index.php

        echo " Copying files to public web root"
        WEBROOT=$SD_ROOT/app/webroot
        cp -pr $WEBROOT/certs $PUBLIC_ROOT/
        cp -pr $WEBROOT/css $PUBLIC_ROOT/
        cp -pr $WEBROOT/files $PUBLIC_ROOT/
        cp -pr $WEBROOT/img $PUBLIC_ROOT/
        cp -pr $WEBROOT/js $PUBLIC_ROOT/
        cp -p $WEBROOT/css.php $PUBLIC_ROOT/
        cp -p $WEBROOT/favicon.ico $PUBLIC_ROOT/
        cp -p $WEBROOT/index.php $PUBLIC_ROOT/

    else
        echo "  Invalid target environment: $TARGET_ENV"
        exit 1
    fi

    echo "  Deploy complete"
    echo ""
}

# Main
SET_PACK_ARG=false
SET_ENV_ARG=false
while getopts "f:t:" opt; do
    case $opt in
        f)
            PACK_ARG=$OPTARG
            SET_PACK_ARG=true
        ;;
        t)
            ENV_ARG=$OPTARG
            SET_ENV_ARG=true
        ;;
        :)
            echo "  Option -$OPTARG requires an argument."
            usage
            exit 1
        ;;
    esac
done

# Call deploy
if [ $SET_PACK_ARG = "true" ] && [ $SET_ENV_ARG = "true" ]; then
    deploy ${ENV_ARG} ${PACK_ARG}
    exit 0
fi

usage
exit 1
