$fh = fopen('people.txt','r') or die($php_errormsg);
$people = fread($fh,filesize('people.txt'));
if (preg_match('/Names:.*(David|Susannah)/i',$people)) {
    print "people.txt matches.";
}
fclose($fh) or die($php_errormsg);