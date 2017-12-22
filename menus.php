<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Date Menus</title>
</head>
<body>
<?php //Script 10.1 - menus.php

function make_date_menus(){
	$months[1] = 'January';
	$months[2] = 'February';
	$months[3] = 'March';
	$months[4] = 'April';
	$months[5] = 'May';
	$months[6] = 'June';
	$months[7] = 'July';
	$months[8] = 'August';
	$months[9] = 'September';
	$months[10] = 'October';
	$months[11] = 'November';
	$months[12] = 'December';
	print '<select name="month">';
	foreach ($months as $key=>$value){
		print "\n<option value=\"$key\">$value</option>";
	}
	print '</select>';
	
	//Make the day pull-down menu:
	print '<select name="day">';
	for($day = 1; $day <=31; $day++){
		print "\n<option value=\"day\">$day</option>";
	}
	print '</select>';
	
	//Make the year pull-down menu:
	print '<select name="year">';
	$start_year = date('Y');
	for($y = $start_year; $y <=($start_year + 10); $y++){
		print "\n<option value=\"$y\">$y</option>";
	}
	print '</select>';
} //End of make_date_menus() function.

//make the form
print '<form action="" method="post">';
make_date_menus();
print '</form>';

?>
</body>
</html>