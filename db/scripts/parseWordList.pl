#!/usr/bin/perl -w
use strict;

my $wordList = "./sat_word_list.txt";
open(FILE,$wordList);
while(<FILE>) {
  chomp();
  if(/^([^,]*),([^,]*),([\s|\"]*)([^\"]*)$/) {
    my $term = $1;
    my $partOfSpeech = $2;
    my $definition = $4;
    #print $term . " " . $partOfSpeech . " " . $definition . "\n";
    print "INSERT INTO cards (question, answer, deck_id) VALUES (\"" . $term . "\", \"(" . $partOfSpeech . ") " . $definition . "\", 1);\n";;
  }
}

