#!/bin/bash -
#
# build-db.sh
#
# Description: Builds studydeck database with optional arguments to
# auto-populate with data.
#
# Prerequisite: $MYSQL_USER must exist so script can login and create database.
# To create $MYSQL_USER in mysql, login as the root user ($ mysql -u root -p) and run:
# 
# mysql> CREATE USER 'username'@'localhost' IDENTIFIED BY 'password';
# Example for this script:
# mysql> CREATE USER 'mysqldev'@'localhost' IDENTIFIED BY 'mysqldev';
#
# Example usage: 
# ./build-db.sh 

SCHEMA_SQL_ORIG=../flashcards.sql
SCHEMA_DB_ORIG_NAME=flashcards
SCHEMA_SQL=/tmp/studydeck.sql

MYSQL_USER=mysqldev
MYSQL_PASSWORD=mysqldev
MYSQL_DB=studydeckdev
MYSQL_EXEC="mysql -u $MYSQL_USER -p${MYSQL_PASSWORD}" 

TMP_DATA_SQL=/tmp/populate_data.sql
DATA_1_SQL=create_some_data.sql


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
    sed "s/${SCHEMA_DB_ORIG_NAME}/${MYSQL_DB}/" $SCHEMA_SQL_ORIG > $SCHEMA_SQL
}

create_schema() { 
    # Run mysqldump to setup empty tables
    echo "  Creating schema"
    $MYSQL_EXEC < $SCHEMA_SQL
} 

populate() {
    # Run additional script to populate DB with data
    echo "  Populating DB with data"
    select_db="USE $MYSQL_DB;"
    echo $select_db > $TMP_DATA_SQL
    cat $DATA_1_SQL >> $TMP_DATA_SQL
    $MYSQL_EXEC < $TMP_DATA_SQL
}

# Main
drop_recreate
rename_schema
create_schema
populate
