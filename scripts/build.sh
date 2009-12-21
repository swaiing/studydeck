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
# 1)  Builds database and entire project minus .svn dirs.
# ./build.sh -t all
#
# 2) Builds project and copies over Steve's config with .svn directories
# ./build.sh -s -t all -c steve


TARGET_STR="----> TARGET:"
DATE=`date`
ROOT_DIR=`dirname $0`
BUILD_DIR=/var/www/html

EXPORT_SVN=true
APP_REPOS=svn+ssh://wais@studydeck.hopto.org/home/svn/studydeck/trunk/app
APP_TMP=/tmp/app
APP_REPOS_OUT=/tmp/app_repos.svn

BUILD_NUM_STR="BUILD_NUM"
DB_SCRIPT=$ROOT_DIR/../db/scripts/build-db.sh
CAKE_BUILD=cake_1.2.3.8166
PROJECT_BUILD=studydeck
CAKE_INSTALL=$ROOT_DIR/../media/${CAKE_BUILD}.tar.gz

CAKE_ROOT=$BUILD_DIR/$PROJECT_BUILD
CAKE_LAYOUT=$BUILD_DIR/$PROJECT_BUILD/app/views/layouts/default.ctp
CAKE_TMP=$BUILD_DIR/$PROJECT_BUILD/app/tmp
CAKE_CACHE=$BUILD_DIR/$PROJECT_BUILD/app/tmp/cache
CAKE_LOGS=$BUILD_DIR/$PROJECT_BUILD/app/tmp/logs
CAKE_SESSIONS=$BUILD_DIR/$PROJECT_BUILD/app/tmp/sessions
CAKE_CONFIG=$BUILD_DIR/$PROJECT_BUILD/app/config

# ----Apache user must be in this group!
GROUP_OWNER=webadm

# ----Production config
DB_CFG=$ROOT_DIR/database.php
CORE_CFG=$ROOT_DIR/core.php

# ----Steve's config
DB_CFG_SHW=$ROOT_DIR/database.php.shw
CORE_CFG_SHW=$ROOT_DIR/core.php.shw

# ----Nicolo's config
DB_CFG_NHG=$ROOT_DIR/database.php.nhg
CORE_CFG_NHG=$ROOT_DIR/core.php.nhg

usage() {
  echo ""
  echo "Usage: `basename $0` [-s] -t [build_db|clean|build] [-c [nicolo|steve]]"
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
  echo "  Unpacking $CAKE_INSTALL and moving to $BUILD_DIR"
  cd /tmp
  zcat $CAKE_INSTALL | tar xf -
  cp -r $CAKE_BUILD $BUILD_DIR/$PROJECT_BUILD

  # Copy /app over cake install
  echo "  Exporting source /app root to $CAKE_ROOT"
  if [ "$EXPORT_SVN" = "true" ]; then
    svn export --force $APP_REPOS $APP_TMP > $APP_REPOS_OUT
  else
    svn checkout $APP_REPOS $APP_TMP > $APP_REPOS_OUT
  fi
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

  # Insert SVN revision number
  if [ "$EXPORT_SVN" = "true" ]; then
    echo "  Inserting build revision"
    tmp_file=/tmp/layout.tmp
    revision=`tail -1 $APP_REPOS_OUT | sed "s/\(Exported revision \)\([0-9]*\)/\2/" | cut -d . -f1`
    sed s/${BUILD_NUM_STR}/${revision}/g ${CAKE_LAYOUT} > $tmp_file
    cp $tmp_file ${CAKE_LAYOUT}
  fi

  # Remove unpacked install
  echo "  Cleaning staged install"
  rm -rf /tmp/$CAKE_BUILD
  rm -rf $APP_TMP
  rm -rf $APP_REPOS_OUT
}

copy_prd_config() {
  # Copy production config files
  echo "  Copying production config files"
  cp $DB_CFG $CAKE_CONFIG/database.php
  cp $CORE_CFG $CAKE_CONFIG/core.php
}

copy_shw_config() {
  # Copy Steve's config files
  echo "  Copying Steve's config files"
  cp $DB_CFG_SHW $CAKE_CONFIG/database.php
  cp $CORE_CFG_SHW $CAKE_CONFIG/core.php
}

copy_nhg_config() {
  # Copy Nicolo's config files
  echo "  Copying Nicolo's config files"
  cp $DB_CFG_NHG $CAKE_CONFIG/database.php
  cp $CORE_CFG_NHG $CAKE_CONFIG/core.php
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
    s)
      EXPORT_SVN=false 
    ;;
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

      elif [ "$OPTARG" = "all" ]; then
        build_db
        clean
        build
        copy_prd_config
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

      echo "  Option -$OPTARG requires and argument."
      usage
      exit 1    
    ;;
  esac
done

exit 0
