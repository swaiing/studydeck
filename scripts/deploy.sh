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

PRD_ROOT=$ROOT_DIR/../public_html
ALPHA_ROOT=$PRD_ROOT/alpha
SD_ROOT=$ALPHA_ROOT/studydeck

usage() {
    echo ""
    echo "Usage: `basename $0` -t [alpha|prd] -f [build-package.tgz]"
    echo ""
}


deploy() {
    echo ""
    echo "----> $USERNAME@$HOSTNAME - DEPLOY"
    echo "  Starting Deploy $DATE"

    echo "  Cleaning $SD_ROOT"
    rm -rf $SD_ROOT

    TARGET_ENV=${1}
    PACKAGE_NAME=${2}
    PACKAGE=${STAGE}/${PACKAGE_NAME}

    if [ $TARGET_ENV = ${ALPHA} ]; then
        echo "  Deploying $PACKAGE to $ALPHA_ROOT"
        tar xvzf $PACKAGE -C $ALPHA_ROOT
    elif [ $TARGET_ENV = ${PRD} ]; then
        echo "  Deploying $PACKAGE to $PRD_ROOT"
        tar xvzf $PACKAGE -C $PRD_ROOT
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
