#!/bin/sh

awk -F, '{printf("INSERT INTO cards (question, answer, deck_id) VALUES (%s,%s,0);\n",$1,$2)}' top500.csv
