#!/bin/sh
#
# Convert meta-M characters to newlines
# tr '\015' '\n' < top500.csv

awk -F, '{printf("INSERT INTO cards (question, answer, deck_id) VALUES (%s,%s,5);\n",$1,$2)}' top500v2_unix.csv
