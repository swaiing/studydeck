#!/bin/bash -
#
# build.sh
#
# Description: Builds studydeck application to specified document root.
#
# Prerequisite: $GROUP_OWNER group must have Apache as a member.
# So that /app/tmp and /app/tmp/cache directories are writeable.
#
# Example: Builds project for Steve.
# ./build.sh /var/www/html -s

BUILD_DIR=$1
CUST_CONFIG=$2

APP_ROOT="../app"
CAKE_BUILD=cake_1.2.3.8166
PROJECT_BUILD=studydeck
CAKE_INSTALL="../media/${CAKE_BUILD}.tar.gz"
DATE=`date`
CAKE_ROOT=$BUILD_DIR/$PROJECT_BUILD
CAKE_TMP=$BUILD_DIR/$PROJECT_BUILD/app/tmp
CAKE_CACHE=$BUILD_DIR/$PROJECT_BUILD/app/tmp/cache
CAKE_LOGS=$BUILD_DIR/$PROJECT_BUILD/app/tmp/logs
CAKE_SESSIONS=$BUILD_DIR/$PROJECT_BUILD/app/tmp/sessions
CAKE_CONFIG=$BUILD_DIR/$PROJECT_BUILD/app/config

# ----Apache user must be in this group!
GROUP_OWNER=webadm

# ----Steve's config
DB_CFG_SHW=database.php.shw
CORE_CFG_SHW=core.php.shw

# ----Nicolo's config
DB_CFG_NHG=database.php.nhg
CORE_CFG_NHG=core.php.nhg

usage() {
  echo "Usage:$0: [build to directory] -[s|n]"
  echo " -s  Copy Steve's config"
  echo " -n  Copy Nicolo's config"
}

build() {
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
  zcat $CAKE_INSTALL | tar xf -
  cp -r $CAKE_BUILD $BUILD_DIR/$PROJECT_BUILD

  # Copy /app over cake install
  echo "  Copying source /app root to $CAKE_ROOT"
  cp -r $APP_ROOT $CAKE_ROOT

  # Change group owner on /app/tmp 
  # Make /app/tmp and /app/tmp/cache group writeable
  echo "  Changing group owner and permissions for app/tmp directory"
  chgrp -R $GROUP_OWNER $CAKE_TMP
  chmod g+rwx $CAKE_TMP
  chmod g+rwx $CAKE_CACHE
  chmod g+rwx $CAKE_LOGS
  chmod g+rwx $CAKE_SESSIONS

  # Remove unpacked install
  echo "  Cleaning staged install"
  rm -r $CAKE_BUILD
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

# Check for empty arg
if [ -z "$BUILD_DIR" ]; then
  usage
  exit 1
fi

# Run build
build

# Copy config files
if [ -z "$CUST_CONFIG" ]; then
  # Inform user to copy/configure config.php, core.php
  echo "  *** Please copy over config.php and database.php to $CAKE_ROOT/app/config"

else
  # Copy Steve's config
  if [ "$CUST_CONFIG" = "-s" ]; then
    copy_shw_config
  fi

  # Copy Nicolo's config
  if [ "$CUST_CONFIG" = "-n" ]; then
    copy_nhg_config
  fi
  
fi
exit 0
