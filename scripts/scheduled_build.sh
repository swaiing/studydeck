#!/bin/bash -

ROOTDIR=`dirname $0`
TARGET_BUILD=$ROOTDIR/"build.sh -t build_app"
LOG=$ROOTDIR/build_studydeck.log
BUILD_OUT=$ROOTDIR/build_studydeck.lastrun.out
DATE=`date`

EMAIL_SUBJ="Studydeck scheduled build on `hostname` completed: $DATE"
EMAIL_RECIPS="stevewai@gmail.com steve@studydeck.com"

# Clear $BUILD_OUT
echo "" > $BUILD_OUT 2>&1
echo "Starting studydeck build on $DATE" >> $BUILD_OUT 2>&1

# Run build
$TARGET_BUILD >> $BUILD_OUT 2>&1

# Append to $LOG
cat $BUILD_OUT > $LOG

# Email build output
cat $BUILD_OUT | mailx -s "${EMAIL_SUBJ}" ${EMAIL_RECIPS}
