<?php
$handle = fopen('C:\My Web Sites\Bon Appetit - delete\www.bonappetit.com\blogsandforums\blogs\projectrecipe\11-coleslaw\index.html', "r");
$contents = stream_get_contents($handle);
echo $contents;
fclose($handle);
?>