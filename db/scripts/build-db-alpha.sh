#!/bin/bash -
#
# build-db-alpha.sh
#
# Description: Builds studydeck database with optional arguments to
# auto-populate with data.
#
# Prerequisite: $MYSQL_USER must exist so script can login and create database.
# This user must have privileges on $MYSQL_DB.
# To create $MYSQL_USER in mysql, login as the root user ($ mysql -u root -p) and run:
# 
# mysql> CREATE USER 'username'@'localhost' IDENTIFIED BY 'password';
# mysql> GRANT SELECT, INSERT, UPDATE, DELETE ON 'database_name'.* to 'username'@"localhost" IDENTIFIED BY 'username';
# 
# Example usage: 
# ./build-db-alpha.sh 

ROOTDIR=`dirname $0`
MYSQL_USER=studydec_root
MYSQL_PASSWORD=password
MYSQL_DB=studydec_alpha
MYSQL_EXEC="mysql -u $MYSQL_USER -p${MYSQL_PASSWORD}" 
SCHEMA_SQL_ORIG=$ROOTDIR/flashcards.sql
SCHEMA_DB_ORIG_NAME=flashcards
SCHEMA_SQL_TMP=/tmp/${MYSQL_DB}.sql


rename_schema() {
    # Rename instances of $SCHEMA_DB_ORIG_NAME in $SCHEMA_SQL_ORIG file
    echo "  Modifying DB name to $MYSQL_DB in $SCHEMA_SQL_ORIG"
    sed "s/${SCHEMA_DB_ORIG_NAME}/${MYSQL_DB}/" $SCHEMA_SQL_ORIG > $SCHEMA_SQL_TMP
}

create_schema() { 
    # Run mysqldump to setup empty tables
    echo "  Creating schema"
    $MYSQL_EXEC < $SCHEMA_SQL_TMP
} 

cleanup() {
  # Clean tmp files
  echo "  Cleaning temporary files"
  rm $SCHEMA_SQL_TMP
}

# Main
rename_schema
create_schema
cleanup
