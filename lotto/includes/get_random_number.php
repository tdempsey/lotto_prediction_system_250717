<script language="php">

/*
##############################################################################
# GetRandomNumber Function                                      Version 1.25 #
# Copyright 2000 Paul Pearson                        wandrer@glcomputers.com #
# Created 7/20/00                                     Last Modified 10/19/00 #
#                                                                            #
# Lic: GPL - Please contact me if you use this function in your own program  #
##############################################################################
*/

/*
##############################################################################
# Description: Get a random number                                           #
##############################################################################
# Arguments: $Random, $Min, $Max                                             #
# Return: $ReturnedValue                                                     #
# Required Variables: none                                                   #
# Bugs: none known                                                           #
# To Do: none                                                                #
#----------------------------------------------------------------------------#
#                                                                            #
# This function uses $Random to pick which random number generator to use:   #
# 3) Computers Random Number Generator                                       #
# 2) HotBits Random Number Generator                                         #
# 1) Random.Org Random Number Generator                                      #
#                                                                            #
# Note: HotBits returns a html page with the random number chosen. Currently #
# we need to strip off the first 464 characters of the page that hotbits     #
# returns so that we can get just the random number. Also, hotbits only      #
# returns a hex number so we have to do some converting to get a number      #
# between $min and $max.                                                     #
#                                                                            #
# Example of Use:                                                            #
#                                                                            #
# $Random_Number_1=GetRandomNumber("$Min_Number", "$Max_Number", "$RNG");    #
# $Random_Number_2=GetRandomNumber("100", "125", "3");                       #
# $Random_Number_3=GetRandomNumber("1","100", "1");                          #
#                                                                            #
##############################################################################
*/

function GetRandomNumber($Min="0", $Max="255", $Random="3") {
	unset ($ReturnedValue);
	
	if ($Random=="3" ) {
		srand((double)microtime()*intval(rand(1,1000000)));
		$ReturnedValue=intval(rand($Min, $Max));
	} 
	if ($Random=="2" ) {
		#
		# HotBits does not use min or max. Instead, it generates a Hex number (00-FF).
		# We fake min/max with hotbits.
		#
		$fp_HotBits = fopen ("http://www.fourmilab.to/cgi-bin/uncgi/Hotbits?nbytes=1&fmt=hex", "r");
		$HotBits_Text = fread ($fp_HotBits, 4096);
		$HotBits_PickedNumber=substr($HotBits_Text, 463, 2);
		fclose($fp_HotBits);
		$ReturnedValue=intval(((hexdec($HotBits_PickedNumber)/255)*($Max-$Min))+$Min);
	}
	
	if ($Random=="1" ) {
		$fp_RandomOrg = fopen ("http://www.random.org/cgi-bin/randnum?num=1&min="."$Min"."&max="."$Max"."&col=1", "r");
		$RandomOrg_Text = fread ($fp_RandomOrg, 4096);
		$ReturnedValue=$RandomOrg_Text;
		fclose($fp_RandomOrg);
	}
return $ReturnedValue;
}
</script>
