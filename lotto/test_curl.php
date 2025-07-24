<?php
$ch = curl_init('C:\My Web Sites\Bon Appetit - delete\www.bonappetit.com\blogsandforums\blogs\projectrecipe\11-coleslaw\index.html'); 
curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE); 
$response = curl_exec($ch); 
echo $response;
echo "cURL Error ($curl_errno): $curl_error\n";
curl_close($ch);
?>