<?php

$table_name= student;

// Connect to server and select databse.
$con = mysql_connect("localhost:3306",'dhe6','dhe6');
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

mysql_select_db("dhe6");

// username and password sent from form 
$myusername=$_POST['myusername']; 
$uin=$_POST['uin']; 

$sql="SELECT * FROM $table_name WHERE username='$myusername' and uin='$uin'";
$result=mysql_query($sql);

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);    //returns the number of rows in a record set

// If result matched $myusername and $uin, there must be only one table row with these inputs
if($count==1)
{
	//session_start();
	$_SESSION['uin']=$uin;	
    $_SESSION['username']=$myusername;	
	echo "<h4>You have logged in successfully</h4>";

}
//else if ($count == 0) { 
//	echo "<h4>Wrong Username or UIN</h4>";
//}





/*
 // you have to open the session to be able to modify or remove it 
 session_start(); 
 
 // to change a variable, just overwrite it 
 $_SESSION['size']='large'; 
 
 //you can remove a single variable in the session 
 unset($_SESSION['shape']); 
 
 // or this would remove all the variables in the session, but not the session itself 
 session_unset(); 
 
 // this would destroy the session variables 
 session_destroy(); 
*/

?>
