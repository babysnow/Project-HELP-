<?php session_start(); ?>

<html>
<title> Learning PHP & MySQL </title>

<head>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<div id="title"  style="font-family:arial;color:white;font-size:30px;background:#f6822b;right:20px">Username: <?php echo $_SESSION['username'] ?> &nbsp;&nbsp;&nbsp;&nbsp;
</div>
<div>
<img src="assets/uiuc_banner.jpg" alt="University of Ilinois" width="120" height="28" style="position: absolute; top:12px; left: 430px"/>
</div>
<div><a href='index2.php' style="font-family:arial;color:white;font-size:20px;background:#f6822b; position: absolute; top: 14px; left: 561px; text-decoration:none"> CS411 Project </a>
</div>
<div style="position: absolute; top: 14px; right: 20px; "> <a href= "mailto:aggarwa3@illinois.edu" style="font-family:arial;color:white;font-size:20px;background:#f6822b; text-decoration:none">Contact</a>
</div>

<table align="center" width="90%">
<tr>
<td>
        <div style="left:50px">
        <img src="assets/help.png" height="70%" alt="Project Help!" />
</div>
</td>

<td>
        <div style="right:50px">
        <?php
        include("main_login2.php");
        include("checklogin2.php");
        if ($_SESSION['uin']!=0)
        {
        echo "<h3><a href='profile2.php'> Go to my Profile</a></h3>";
        }
        ?>
        </div>
</td>
</tr>
</table>


<table width="90%" align="center">
<tr>
<td>
<div align="center">
<form action="index2.php" method="post">
<h4>Please select your category:</h4>
<table border="0">
<tr>
<td>
        <input type="radio" name="item"  value="Courses"/> Courses<br />
</td>
<td>
        <input type="radio" name="item" value="Organizations" /> Organizations<br />
</td>
<td>
        <input type="radio" name="item" value="Restaurant"/> Restaurant<br />
</td>
<td>
        <input type="submit" value="Submit" class="cupid-blue">
</td>
</tr>
</table>
</form>
</div>
</td>

<td>
<div align="right">
<form name="form" action="index2.php" method="get">
Quick Course search:   <input type="text" name="q" />
  <input type="submit" name="Submit" value="Search" class="cupid-blue" />
</form>
</div>
</td>
</tr>
</table>



<!--<h3> Selected Category is <?php echo $_POST['item'];?></h3>-->

</br></br></br>




<?php
//echo $_SESSION['uin'];
 $con = mysql_connect("localhost:3306",'dhe6','dhe6');
 if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

 mysql_select_db("dhe6");

 $selection = Courses;
 $query = "SELECT * FROM $selection where ";  
 $result = mysql_query($query); 

if ($_POST['item'] != NULL )
{
//echo "<h3 >"."Selected Category is  ".$_POST['item'];"</h3>";
echo "<table border='1' align='center'>";
	echo "<tr>";
		echo "<th>" .ID.    "</th>";
		echo "<th>" .Name.    "</th>";
		echo "<th>" .CourseCode. "</th>";
		echo "<th>" .Description. "</th>";
		echo "<th>" .Credits. "</th>";
		echo "<th>" .Click." ".to." ".Add. "</th>";
	echo "</tr>";
	 
	while($row = mysql_fetch_array($result))
	{
	echo "<tr>";
		 echo "<td>" .$row['Id']. "</td>";
		 echo "<td>" .$row['Name']. "</td>";
		 echo "<td>" .$row['CourseCode']. "</td>";
		 echo "<td>" .$row['Descripton']."</td>";
		 echo "<td>" .$row['Credits']."</td>";
		 echo "<td><center><a href="."index2.php?Id=".$row['Id'].">View</a></center>"."</td>";
	echo "</tr>";
	}

echo "</table>";
}

?>


<?php
	if ($_GET['Id']!=0)
	{
		$Id = $_GET['Id'];
		$query   = "SELECT CourseCode FROM Courses where Id=".$Id."";
		$result=mysql_query($query) ;
		$row = mysql_fetch_array($result);
		//echo $row['CourseCode'];
		$query2 =  "SELECT * FROM CourseMeetings where CourseCode="."'".$row['CourseCode']."'"."";
		$result2=mysql_query($query2);

		echo "<table border='1' align='center'>";
		 echo "<tr>";
			 echo "<th>" .CourseCode.    "</th>";
			echo "<th>" .CRN.    "</th>";
			echo "<th>" .Start. "</th>";
			echo "<th>" .End. "</th>";
			echo "<th>" .Days. "</th>";
			echo "<th>" .Classroom. "</th>";
			echo "<th>" .Click." ".to." ".Add. "</th>";
		 echo "</tr>";

		while($row2 = mysql_fetch_array($result2))
		{
			echo "<tr>";
	  			echo "<td>" .$row2['CourseCode']. "</td>";
	  			echo "<td>" .$row2['CRN']. "</td>";
				echo "<td>" .$row2['Start']. "</td>";
				echo "<td>" .$row2['End']."</td>";
				echo "<td>" .$row2['Days']."</td>";
				echo "<td>" .$row2['Classroom']."</td>";
				echo "<td><center><a href="."index2.php?CRN=".$row2['CRN'].">Add</a></center>"."</td>";			
			echo "</tr>";
		}
		echo "</table>";
	
	}


 if ($_SESSION['uin']==0)
 {
 echo "To add courses, you need to first Login.";
 }
 else
 {
	 if ($_GET['CRN']!=0)
	{
  	$CRN = $_GET['CRN'];
  	echo "CRN = ".$CRN."</br>";
  
  	$query   = "SELECT * FROM CourseMeetings where CRN=".$CRN."";
  	$result = mysql_query($query); 
  	$row = mysql_fetch_array($result);
  
    $query2 =  "insert into studentcourse (uin,crn) values('".$_SESSION['uin']."','".$row['CRN']."');";
    $result2=mysql_query($query2) ;
    echo "Course added to your database.";
    echo "<h3><a href='index2.php'> back to main page</a></h3>";
	}
}



?>

</br></br></br>
<form name="fill" method="post" action="filling_tables.php">
<td><input type="submit" name="Submit" value="Fill in the DB" class="cupid-blue"></td>
</form>




</body>
</html>
