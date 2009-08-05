#!/bin/bash -

APP_ROOT="../app"
CAKE_BUILD=cake_1.2.3.8166
PROJECT_BUILD=studydeck_beta
CAKE_INSTALL="../media/${CAKE_BUILD}.tar.gz"
DATE=`date`
BUILD_DIR=$1
CAKE_ROOT=$BUILD_DIR/$PROJECT_BUILD
GROUP_OWNER=webadm

usage() {
  echo "Usage:$0: [build to directory]"
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

  # Unpack cake tarball
  echo "  Unpacking $CAKE_INSTALL"
  zcat $CAKE_INSTALL | tar xf -
  mv $CAKE_BUILD $PROJECT_BUILD
  mv $PROJECT_BUILD $BUILD_DIR/

  # Copy /app over cake install
  echo "  Copying /app source"
  cp -pr $APP_ROOT $CAKE_ROOT/

  # Change to group permissions to make it writeable
  echo "  Changing permissions on cache directories"
  #chgrp -R $GROUP_OWNER $CAKE_ROOT

  # Inform user to copy/configure config.php, core.php
  echo "  Please copy over config.php"

}

# Check for empty arg
if [ -z "$BUILD_DIR" ]; then
  usage
  exit 1
fi

# Run build
build
exit 0
