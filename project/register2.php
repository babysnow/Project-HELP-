<?php

if ( $_POST['firstname'] && $_POST['regemail'] && $_POST['uin1'] && $_POST['uin2'] && $_POST['username'] && $_POST['major'] && ($_POST['uin1']==$_POST['uin2']))
{

		$con = mysql_connect("localhost:3306",'dhe6','dhe6');
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db("dhe6");
               $query1 = "select * from student where uin = " .$_POST['uin1'];
               $result1 = mysql_query($query1) or die ("error in query");
               $numrows=mysql_num_rows($result1);
               if ($numrows > 0) {
                 echo "<p>There exists one account with identical uin</p>";
                 echo "<h3><a href='registerform2.php'>Back to register page</a></h3>";
                 echo "<h3><a href='index2.php'>Back to main page</a></h3>";
                 exit;
               }

	       $query="insert into student (name,uin,email,major,username) values('".$_POST['firstname']."','".$_POST['uin1']."','".$_POST['regemail']."','".$_POST['major']."','".$_POST['username']."');";

		//$query="INSERT INTO student (name,uin,email,major,username) VALUES ('$_POST[firstname]','$_POST[uin1]','$_POST[regemail]','$_POST[major]','$_POST[username]')";
 	$result=mysql_query($query)  or die ("error in query");
 	echo "<h1>You have registered sucessfully</h1>";
 	//echo "<a href='index.php'>Go to main page</a>";
}

else
{
 echo "Invaild data or Password do not match !!!";
 echo "<h3><a href='registerform2.php'>Try again</a></h3>";
}

echo "<h3><a href='index2.php'>back to main page</a></h3>";
?>



