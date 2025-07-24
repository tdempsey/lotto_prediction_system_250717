<?php 
        // create curl resource 
        $ch = curl_init(); 

		echo "opening http://recipedia.tdempsey.com<br>";

        // set url 
        #curl_setopt($ch, CURLOPT_URL, "localhost/lotto/test_curl.incl"); 
		curl_setopt($ch, CURLOPT_URL, "file:///C:/My%20Web%20Sites/Alabama/www.ilovealabamafood.com/alabama-food15-alabama-cookbooks-you-should-have-in-your-kitchen/index.html"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 

		echo "$output";

        // close curl resource to free up system resources 
        curl_close($ch);      
?>