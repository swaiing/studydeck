#!/bin/bash -
#
# build-db.sh
#
# Description: Builds studydeck database with optional arguments to
# auto-populate with data.
#
# Example: Builds project for Steve.
# ./build-db.sh full?

SCHEMA_SQL=../flashcards.sql

MYSQL_USER=mysqldev
MYSQL_PASSWORD=mysqldev
MYSQL_DB=studydeckdev


