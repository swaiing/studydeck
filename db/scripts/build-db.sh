#!/bin/bash -
#
# build-db.sh
#
# Description: Builds studydeck database with optional arguments to
# auto-populate with data.
#
# Prerequisite: $MYSQL_USER must exist so script can login and create database.
# This user must have all privileges on $MYSQL_DB.  Another user must also be created 
# as the application user with only SELECT, INSERT, UPDATE and DELETE privileges.
#
# To create $MYSQL_USER in mysql, login as the root user ($ mysql -u root -p) and run:
#
# Script User:
# mysql> CREATE USER 'username'@'localhost' IDENTIFIED BY 'password';
# mysql> GRANT ALL PRIVILEGES ON 'database_name'.* to 'username'@"localhost" IDENTIFIED BY 'username';
#
# Application User:
# mysql> CREATE USER 'username'@'localhost' IDENTIFIED BY 'password';
# mysql> GRANT SELECT, INSERT, UPDATE, DELETE ON 'database_name'.* to 'username'@"localhost" IDENTIFIED BY 'username';
# 
# Example for this script:
# mysql> CREATE USER 'mysqladm'@'localhost' IDENTIFIED BY 'mysqladm';
# mysql> GRANT ALL PRIVILEGES ON studydeck.* to mysqladm@"localhost" IDENTIFIED BY 'mysqladm';
#
# mysql> CREATE USER 'mysqldev'@'localhost' IDENTIFIED BY 'mysqldev';
# mysql> GRANT SELECT, INSERT, UPDATE, DELETE ON studydeck.* to mysqldev@"localhost" IDENTIFIED BY 'mysqldev';
#
# Example usage: 
# ./build-db.sh 

ROOTDIR=`dirname $0`
SCHEMA_SQL_ORIG=$ROOTDIR/../flashcards.sql
SCHEMA_DB_ORIG_NAME=flashcards
SCHEMA_SQL_TMP=/tmp/studydeck.sql

MYSQL_USER=mysqladm
MYSQL_PASSWORD=mysqladm
MYSQL_DB=studydeck
MYSQL_EXEC="mysql -u $MYSQL_USER -p${MYSQL_PASSWORD}" 

TMP_DATA_SQL=/tmp/populate_data.sql
DATA_1_SQL=$ROOTDIR/create_data.sql
DATA_2_SQL=$ROOTDIR/top500v2_ordered.sql
DATA_3_SQL=$ROOTDIR/latin_roots_ordered.sql


drop_recreate() {
    # Drop db
    echo "  Dropping database $MYSQL_DB"
    drop_db_sql="DROP DATABASE IF EXISTS $MYSQL_DB;"
    echo $drop_db_sql | $MYSQL_EXEC

    # Create db and grant privileges
    echo "  Creating database $MYSQL_DB"
    create_db_sql="CREATE DATABASE $MYSQL_DB;"
    #grant_priv_sql="GRANT ALL PRIVILEGES ON ${MYSQL_DB}.* to ${MYSQL_USER}@\"localhost\" IDENTIFIED BY \"${MYSQL_PASSWORD}\";"
    echo $create_db_sql | $MYSQL_EXEC
}

rename_schema() {
    # Rename instances of $SCHEMA_DB_ORIG_NAME in $SCHEMA_SQL_ORIG file
    echo "  Modifying DB name to $MYSQL_DB in $SCHEMA_SQL_ORIG"
    sed "s/${SCHEMA_DB_ORIG_NAME}/${MYSQL_DB}/" $SCHEMA_SQL_ORIG > $SCHEMA_SQL_TMP
}

create_schema() { 
    # Run mysqldump to setup empty tables
    echo "  Creating schema"
    $MYSQL_EXEC < $SCHEMA_SQL_ORIG
} 

populate() {
    # Run additional script to populate DB with data
    echo "  Populating DB with data"
    select_db="USE $MYSQL_DB;"
    echo $select_db > $TMP_DATA_SQL
    cat $DATA_1_SQL >> $TMP_DATA_SQL
    cat $DATA_2_SQL >> $TMP_DATA_SQL
    cat $DATA_3_SQL >> $TMP_DATA_SQL
    $MYSQL_EXEC < $TMP_DATA_SQL
}

cleanup() {
  # Clean tmp files
  echo "  Cleaning temporary files"
  if [ -e $SCHEMA_SQL_TMP ]; then
    rm $SCHEMA_SQL_TMP
  fi
  if [ -e $TMP_DATA_SQL ]; then
    rm $TMP_DATA_SQL
  fi
}

# Main
drop_recreate
# SHW - Nov 14, 2010 - Removed rename
#rename_schema
create_schema
populate
cleanup
