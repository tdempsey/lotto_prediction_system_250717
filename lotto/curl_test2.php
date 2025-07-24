<?php
function curl_download($Url){
    if (!function_exists('curl_init')){
        die('cURL is not installed. Install and try again.');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

print curl_download('https://en.wikipedia.org/wiki/Thomas_Wyatt_(poet)');
?>