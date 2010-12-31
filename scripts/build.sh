#!/bin/bash -
#
# build.sh
#
# Description: Builds studydeck application to specified document root.
#
# Prerequisite: $GROUP_OWNER group must have Apache as a member.
# So that /app/tmp and /app/tmp/cache directories are writeable.
#
# Examples:
#
# 1) Builds database and entire project minus
# ./build.sh -t all
#
# 2) Builds project and copies over steve's config
# ./build.sh -t all
#
# 3) Build app to staging and push to production
# ./build.sh -t host_prd


DATE=`date`
DATESTAMP=`date "+%Y%m%d.%H%M"`
ROOT_DIR=`dirname $0`
TARGET_STR="----> TARGET:"

# Production build vars
STAGING=~/stage
HOST_USER=studydec
HOST_SERVER=studydeck.com
HOST_STAGE_DIR=/home4/studydec/stage
PRD_TOKEN=prd
ALPHA_TOKEN=alpha

BUILD_DIR=/var/www/html
DB_SCRIPT=$ROOT_DIR/../db/scripts/build-db.sh
SOURCE_LOCATION=$ROOT_DIR/../root
PROJECT_BUILD=studydeck
CAKE_ROOT=$BUILD_DIR/$PROJECT_BUILD

# ----Apache user must be in this group!
GROUP_OWNER=webadm

# ----Production config
DB_CFG=$ROOT_DIR/database.php.prd
CORE_CFG=$ROOT_DIR/core.php.prd

usage() {
  echo ""
  echo "Usage: `basename $0` -t [build_db|build_app|clean|build|all|host_alpha|host_prd]"
  echo ""
}

clean() {
  echo ""
  echo "  $TARGET_STR clean"
  if [ -d $CAKE_ROOT ]; then
    echo "  Removing $CAKE_ROOT"
    rm -rf $CAKE_ROOT
  fi
}

build() {
  echo ""
  echo "  $TARGET_STR build"
  echo "  Starting Build $DATE"

  # Check if $BUILD_DIR has been passed
  if [ ! -z ${1} ]; then
    echo "  Setting BUILD_DIR to ${1}"
    BUILD_DIR=${1}
    [ ! -d ${BUILD_DIR} ] && mkdir -p ${BUILD_DIR}
  fi

  # Set some directory locations since BUILD_DIR has been established
  CAKE_ROOT=$BUILD_DIR/$PROJECT_BUILD
  README=$CAKE_ROOT/Studydeck_README
  CAKE_TMP=$CAKE_ROOT/app/tmp
  CAKE_CACHE=$CAKE_ROOT/app/tmp/cache
  CAKE_LOGS=$CAKE_ROOT/app/tmp/logs
  CAKE_SESSIONS=$CAKE_ROOT/app/tmp/sessions
  CAKE_CONFIG=$CAKE_ROOT/app/config

  # Check if $BUILD_DIR is writeable
  if [ ! -d $BUILD_DIR ] || [ ! -w $BUILD_DIR ]; then
    echo "  [ERROR] $BUILD_DIR: Not writeable"
    exit 1
  fi

  # Check if Cake build already exists there
  if [ -e $CAKE_ROOT ]; then
    echo "  [ERROR] $CAKE_ROOT: Build already exists"
    exit 1
  fi

  # Check if GROUP_OWNER is valid on host
  grep $GROUP_OWNER /etc/group > /dev/null
  if [ $? = 1 ]; then
    echo "  [ERROR] $GROUP_OWNER: Invalid group"
    exit 1
  fi

  # Copy /root, which contains Cake 1.2 + application code from source
  echo "  Copying source files to $CAKE_ROOT"
  mkdir $CAKE_ROOT
  cp -pr $SOURCE_LOCATION/* $CAKE_ROOT

  # Change group owner on /app/tmp 
  # Make /app/tmp and /app/tmp/cache group writeable
  echo "  Changing group owner and permissions for app/tmp directory"
  chgrp -R $GROUP_OWNER $CAKE_TMP
  chmod g+rwx $CAKE_TMP
  chmod g+rwx $CAKE_CACHE
  chmod g+rwx $CAKE_LOGS
  chmod g+rwx $CAKE_SESSIONS

  # Create PACKAGE_INFO file
  echo "  Creating README file"
  echo "Built Studydeck on: " > $README;
  echo $DATE >> $README
  
  # Echo completion
  echo "  Build complete!"
}

package() {
  echo ""
  echo "  $TARGET_STR package"

  PKG_NAME="studydeck-${DATESTAMP}.tgz"
  echo "  Creating package tarball, ${PKG_NAME}"
  cd ${STAGING}
  tar czf $PKG_NAME ${PROJECT_BUILD}

  echo "  Removing stage dir ${PROJECT_BUILD}"
  rm -rf ${PROJECT_BUILD}
}

sendpkg() {
  echo ""
  echo "  $TARGET_STR sendpkg"
  echo "  Transferring package (${PKG_NAME}) to studydeck.com"
  scp ${STAGING}/${PKG_NAME} ${HOST_USER}@${HOST_SERVER}:${HOST_STAGE_DIR}
}

unpack() {
  echo ""
  echo "  $TARGET_STR unpack"
  echo "  Invoking deploy.sh on host to unpack"

  # Validate $TOKEN
  ENV_TOKEN=${1}
  if [ $ENV_TOKEN != "$ALPHA_TOKEN" ] && [ $ENV_TOKEN != "$PRD_TOKEN" ]; then
    echo "  [ERROR] Environment: $ENV_TOKEN undefined"
    exit 1
  fi

  # Call script
  ssh ${HOST_USER}@${HOST_SERVER} "~/scripts/deploy.sh -t ${ENV_TOKEN} -f ${PKG_NAME}"
}

copy_prd_config() {
  # Copy production config files
  echo "  Copying production config files"
  cp ${DB_CFG} $CAKE_CONFIG/database.php
  cp ${CORE_CFG} $CAKE_CONFIG/core.php
}

build_db() {
  # Run build-db.sh script
  echo ""
  echo "  $TARGET_STR build_db"
  $DB_SCRIPT
}

# Get options
while getopts "t:" opt; do
  case $opt in
    t)
      if [ "$OPTARG" = "clean" ]; then
        clean
        exit 0

      elif [ "$OPTARG" = "build" ]; then
        build
        exit 0
      
      elif [ "$OPTARG" = "build_db" ]; then
        build_db
        exit 0

      elif [ "$OPTARG" = "build_app" ]; then
    	clean
        build
        exit 0

      elif [ "$OPTARG" = "host_prd" ]; then
        build ${STAGING}
    	copy_prd_config
        package
        sendpkg
        unpack $PRD_TOKEN
        exit 0

      else
        echo "  Invalid target: $OPTARG"
        exit 1
      fi
    ;;
    :)

      echo "  Option -$OPTARG requires an argument."
      usage
      exit 1    
    ;;
  esac
done

usage
exit 1
