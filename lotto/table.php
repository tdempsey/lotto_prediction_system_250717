$query = "DROP TABLE IF EXISTS $draw_prefix";
$query .= "temp_col_";
$query .= "$cola";
$query .= "_$colb ";

$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));

$query = "CREATE TABLE $draw_prefix";
$query .= "temp_col_";
$query .= "$cola";
$query .= "_$colb (";
$query .= "id int(10) unsigned NOT NULL auto_increment, ";
$query .= "num1 tinyint(3) unsigned NOT NULL default '0', ";
$query .= "num2 tinyint(3) unsigned NOT NULL default '0', ";
$query .= "cnt10 tinyint(4) unsigned NOT NULL default '0', ";
$query .= "cnt30 tinyint(4) unsigned NOT NULL default '0', ";
$query .= "cnt50 tinyint(4) unsigned NOT NULL default '0', ";
$query .= "cnt100 tinyint(4) unsigned NOT NULL default '0', ";
$query .= "cnt365 tinyint(4) unsigned NOT NULL default '0', ";
$query .= "cnt500 tinyint(4) unsigned NOT NULL default '0', ";
$query .= "cnt1000 tinyint(4) unsigned NOT NULL default '0', ";
$query .= "cnt5000 tinyint(4) unsigned NOT NULL default '0', ";
$query .= "prev_draw date NOT NULL default '1962-08-17', ";
$query .= "last_draw date NOT NULL default '1962-08-17', ";
$query .= "percent_365 float (4,1) unsigned NOT NULL default '0', ";
$query .= "percent_5000 float (4,1) unsigned NOT NULL default '0', ";
$query .= "percent_wa float (4,1) unsigned NOT NULL default '0', ";
$query .= "PRIMARY KEY  (id), ";
$query .= "KEY num1 (num1), ";
$query .= "KEY num2 (num2) ";
$query .= ") TYPE=MyISAM ";

$mysqli_result = mysqli_query($mysqli_link, $query) or die (mysqli_error($mysqli_link));