<?php

$con = mysql_connect("localhost:3306",'dhe6','dhe6');
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}
mysql_select_db("dhe6");
echo "sdasdasda";
//mysql_query("INSERT INTO Courses VALUES('Freshman Orientation','100','30094','ntroduction to Computer Science as a field and career for computer science majors. Overview of //the field and specific examples of problem areas and methods of solution.',1)");








?>
