#!/bin/sh

awk -F, '{printf("INSERT INTO cards (question, answer, deck_id) VALUES (%s,%s,0);\n",$1,$3)}' sat_word_list.txt
