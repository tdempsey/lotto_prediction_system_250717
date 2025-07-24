<?php
  $host_name = 'db5000712110.hosting-data.io';
  $database = 'dbs653765';
  $user_name = 'dbu493141';
  $password = '<Enter your password here.>';
  $connect = mysql_connect($host_name, $user_name, $password, $database);

  if (mysql_errno()) {
    die('<p>Failed to connect to MySQL: '.mysql_error().'</p>');
  } else {
    echo '<p>Connection to MySQL server successfully established.</p >';
  }
?>