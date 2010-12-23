#!/usr/bin/perl


my $path = "./top500v2.sql";
open (FILE, $path);
my $i = 1;
while (<FILE>) {
    chomp;
    # skip commented out lines
    if (/^--/) { next; }
    my $line = $_;
    if (/(^[^(]*\()([^(]*\()(.*)$/) {
        print $1 . "card_order, " . $2 . $i . "," . $3 . "\n";
        $i++;
    }
}
close(FILE);
