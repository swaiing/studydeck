#!/usr/bin/perl -w
use strict;

my $wordList = "./state_capitals.list";
open(FILE,$wordList);
while(<FILE>) {
  chomp();
   if(/^(.*),(.*)$/) {
    my $state = $1;
    my $capital = $2;
    #print $term . " " . $partOfSpeech . " " . $definition . "\n";
    print "INSERT INTO cards (question, answer, deck_id) VALUES (\"" . $state . "\", \"" . $capital . "\", 2);\n";;
  }
}

