#!/bin/bash -
#
# build-db-gre.sh
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
# ./build-db-gre.sh 

ROOTDIR=`dirname $0`
MYSQL_USER=studydec_root
MYSQL_PASSWORD=password
MYSQL_DB=studydec_gre
MYSQL_EXEC="mysql -u $MYSQL_USER -p${MYSQL_PASSWORD}" 
SCHEMA_SQL_ORIG=$ROOTDIR/flashcards.sql
SCHEMA_DB_ORIG_NAME=studydeck
SCHEMA_SQL_TMP=/tmp/${MYSQL_DB}.sql

TMP_DATA_SQL=/tmp/populate_data.sql
DATA_1_SQL=$ROOTDIR/create_data.sql
DATA_2_SQL=$ROOTDIR/top500v2_ordered.sql
DATA_3_SQL=$ROOTDIR/latin_roots_ordered.sql

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
  rm $SCHEMA_SQL_TMP
}

# Main
rename_schema
create_schema
populate
cleanup
