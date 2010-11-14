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
# 1)  Builds database and entire project minus
# ./build.sh -t all
#
# 2) Builds project and copies over Steve's config
# ./build.sh -t all -c steve


DATE=`date`
DATESTAMP=`date "+%Y%m%d.%H%M"`
ROOT_DIR=`dirname $0`
TARGET_STR="----> TARGET:"

# Removed for SVN
#EXPORT_SVN=true
#SVN_USER=webadm
#APP_REPOS=svn+ssh://${SVN_USER}@studydeck.hopto.org/home/svn/studydeck/trunk/app
#APP_REPOS_OUT=/tmp/app_repos.svn

HOSTED_BUILD=false
APP_TMP=/tmp/app
LAYOUT_TMP=/tmp/layout.tmp.`whoami`
APP_HOME=$ROOT_DIR/../app

STAGING=~/stage
HOST_USER=studydec
HOST_SERVER=studydeck.com
HOST_STAGE_DIR=/home4/studydec/stage
PRD_TOKEN=prd
ALPHA_TOKEN=alpha

BUILD_DIR=/var/www/html
BUILD_NUM_STR="BUILD_NUM"
DB_SCRIPT=$ROOT_DIR/../db/scripts/build-db.sh
CAKE_BUILD=cake_1.2.3.8166
PROJECT_BUILD=studydeck
CAKE_INSTALL=$ROOT_DIR/../media/${CAKE_BUILD}.tar.gz
TMP=/tmp
CAKE_INSTALL_TMP=${TMP}/${CAKE_BUILD}.tar.gz
CAKE_BUILD_TMP=${TMP}/${CAKE_BUILD}
CAKE_ROOT=$BUILD_DIR/$PROJECT_BUILD

# ----Apache user must be in this group!
GROUP_OWNER=webadm

# ----Production config
DB_CFG=$ROOT_DIR/database.php
CORE_CFG=$ROOT_DIR/core.php

usage() {
  echo ""
  echo "Usage: `basename $0` -t [build_db|build_app|clean|build|all|host_alpha|host_prd] [-c [nicolo|steve]]"
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
  PACKAGE_INFO=$BUILD_DIR/$PROJECT_BUILD/BUILD_README
  CAKE_LAYOUT=$BUILD_DIR/$PROJECT_BUILD/app/views/layouts/default.ctp
  CAKE_TMP=$BUILD_DIR/$PROJECT_BUILD/app/tmp
  CAKE_CACHE=$BUILD_DIR/$PROJECT_BUILD/app/tmp/cache
  CAKE_LOGS=$BUILD_DIR/$PROJECT_BUILD/app/tmp/logs
  CAKE_SESSIONS=$BUILD_DIR/$PROJECT_BUILD/app/tmp/sessions
  CAKE_CONFIG=$BUILD_DIR/$PROJECT_BUILD/app/config

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

  # Unpack cake tarball, rename and move to BUILD_DIR
  #echo "  Unpacking $CAKE_INSTALL and moving to $BUILD_DIR"
  echo "  Unpacking $CAKE_INSTALL and moving to $CAKE_INSTALL_TMP"
  cp -p $CAKE_INSTALL $CAKE_INSTALL_TMP
  zcat $CAKE_INSTALL_TMP | tar -C $TMP -xf -
  cp -r $CAKE_BUILD_TMP $BUILD_DIR/$PROJECT_BUILD

  # Copy /app over cake install
  echo "  Exporting source /app root to $CAKE_ROOT"
  cp -r $APP_HOME $APP_TMP

  echo "  Copying $APP_TMP to $CAKE_ROOT"
  cp -r $APP_TMP $CAKE_ROOT

  # Change group owner on /app/tmp 
  # Make /app/tmp and /app/tmp/cache group writeable
  echo "  Changing group owner and permissions for app/tmp directory"
  chgrp -R $GROUP_OWNER $CAKE_TMP
  chmod g+rwx $CAKE_TMP
  chmod g+rwx $CAKE_CACHE
  chmod g+rwx $CAKE_LOGS
  chmod g+rwx $CAKE_SESSIONS

  # TODO: Remove "BUILD_NUM_STR" from layout
  #revision=`tail -1 $APP_REPOS_OUT | sed "s/\(Exported revision \)\([0-9]*\)/\2/" | cut -d . -f1`
  revision=someRevision

  if [ "$HOSTED_BUILD" = "true" ]; then
    echo "  Removing ${BUILD_NUM_STR} from layout"
    match="Build Version: ${BUILD_NUM_STR}\. "
    sed s/"${match}"//g ${CAKE_LAYOUT} > $LAYOUT_TMP
    cp $LAYOUT_TMP $CAKE_LAYOUT

  # Insert SVN revision number if doing non-production build
  else
      if [ "$EXPORT_SVN" = "true" ]; then
	# TODO: Get an actual build revision number
        echo "  Inserting build revision"
        sed s/${BUILD_NUM_STR}/${revision}/g ${CAKE_LAYOUT} > $LAYOUT_TMP
        cp $LAYOUT_TMP $CAKE_LAYOUT
      fi
  fi

  # Create PACKAGE_INFO file
  echo $DATE > $PACKAGE_INFO
  # TODO: More information about package
  #cat $APP_REPOS_OUT >> $PACKAGE_INFO

  # Remove unpacked install
  echo "  Cleaning staged install"
  rm -rf $CAKE_INSTALL_TMP
  rm -rf $CAKE_BUILD_TMP
  rm -rf $APP_TMP
  rm -rf $APP_REPOS_OUT
  rm -rf $LAYOUT_TMP
}

package() {
  echo ""
  echo "  $TARGET_STR package"

  PKG_NAME="studydeck-${revision}.tgz"
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

copy_alpha_config() {
  # Copy production config files
  echo "  Copying production config files"
  cp ${DB_CFG}.alpha $CAKE_CONFIG/database.php
  cp ${CORE_CFG}.alpha $CAKE_CONFIG/core.php
}

copy_prd_config() {
  # Copy production config files
  echo "  Copying production config files"
  cp ${DB_CFG}.prd $CAKE_CONFIG/database.php
  cp ${CORE_CFG}.prd $CAKE_CONFIG/core.php
}

copy_dev_config() {
  # Copy production config files
  echo "  Copying production config files"
  cp ${DB_CFG}.dev $CAKE_CONFIG/database.php
  cp ${CORE_CFG}.dev $CAKE_CONFIG/core.php
}

copy_shw_config() {
  # Copy Steve's config files
  echo "  Copying Steve's config files"
  cp ${DB_CFG}.shw $CAKE_CONFIG/database.php
  cp ${CORE_CFG}.shw $CAKE_CONFIG/core.php
}

copy_nhg_config() {
  # Copy Nicolo's config files
  echo "  Copying Nicolo's config files"
  cp ${DB_CFG}.nhg $CAKE_CONFIG/database.php
  cp ${CORE_CFG}.nhg $CAKE_CONFIG/core.php
}

build_db() {
  # Run build-db.sh script
  echo ""
  echo "  $TARGET_STR build_db"
  $DB_SCRIPT
}

# Get options
while getopts "st:c:" opt; do
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
    	copy_dev_config
        exit 0

      elif [ "$OPTARG" = "host_prd" ]; then
        HOSTED_BUILD=true
        build ${STAGING}
    	copy_prd_config
        package
        sendpkg
        unpack $PRD_TOKEN
        exit 0

      elif [ "$OPTARG" = "host_alpha" ]; then
        HOSTED_BUILD=true
        build ${STAGING}
    	copy_alpha_config
        package
        sendpkg
        unpack $ALPHA_TOKEN
        exit 0

      elif [ "$OPTARG" = "all" ]; then
        build_db
        clean
        build
        copy_dev_config
        exit 0

      else
        echo "  Invalid target: $OPTARG"
        exit 1
      fi
    ;;
    c)
      if [ "$OPTARG" = "steve" ]; then
        copy_shw_config
      elif [ "$OPTARG" = "nicolo" ]; then
        copy_nhg_config
      else
        echo "  Invalid config: $OPTARG"
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
