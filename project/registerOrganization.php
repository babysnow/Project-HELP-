<?php

if ( $_POST['name'] && $_POST['description'] && $_POST['manager_name'] && $_POST['manager_email'] )
{

		$con = mysql_connect("localhost:3306",'dhe6','dhe6');
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db("dhe6");

	       $query="insert into Organizations (Name,Description,ManagerName,ManagerEmail) values('".$_POST['name']."','".$_POST['description']."','".$_POST['manager_name']."','".$_POST['manager_email']."');";

 	$result=mysql_query($query)  or die ("error in query");
 	echo "<h1>You have added Organization sucessfully</h1>";
}

else
{
 echo "Invaild data or Password do not match !!!";
 echo "<h3><a href='organizations2.php'>Try again</a></h3>";
}

echo "<h3><a href='index2.php'>back to main page</a></h3>";
?>



