<?php
$dbhost = 'localhost'; 
$dbuser = 'august'; 
$dbpass = '4rC3JJLvVeYuzyNh'; 
$dbname = 'gnomes'; 


$db_con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

$query = 'SELECT * FROM gno1_arch_country';

$result = mysqli_query($db_con, $query);

echo mysqli_num_rows($result) .'<br /><br />country<br /><br />';

if (mysqli_num_rows($result) > 0)
while ($row = mysqli_fetch_object($result)) {
	$query = 'UPDATE gno1_arch_region SET country_code = "'. $row->code .'" WHERE country_id = '. $row->id; 
	$result2 = mysqli_query($db_con, $query);
	echo $query .'<br />'. mysqli_error($db_con);
}

