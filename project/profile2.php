<?php session_start(); ?>

<html>
<title> User Profile </title>
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
        ?>
        </div>
</td>
</tr>
</table>



 <!-- <input id="info" onclick="displayinfo()" type="submit" value="Display my stored information" />-->
<div align="center">
<table>
 <a href='profile2.php?id=info'> Display my Courses information</a></br>
 <a href='profile2.php?id=orginfo'> Display my Organizations</a></br>
<?php
 if ($_SESSION['username'] == 'admin') {
   echo "<a href='http://dmserv3.cs.illinois.edu/~dhe6/uiuc_course_list.php'>Manage Course DB</a></br>";
 }
?>
 <a href='index2.php'> Back to main page</a></br>
</table>
</div>
</br></br>
  
<?php
if ($_GET['id'] ==info)
{
  $con = mysql_connect("localhost:3306",'dhe6','dhe6');
 		if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

  mysql_select_db("dhe6");
   
  //$query   = "SELECT * FROM courses where id=".$id."";
  //$result = mysql_query($query); 
  //$row = mysql_fetch_array($result);
   
  $query3 = "select * from student where uin='".$_SESSION['uin']."'";
  $result3 = mysql_query($query3);
  
  while($row3 = mysql_fetch_array($result3))
  {
  echo "<div align='center'>";
  echo "<table id='mytable'";
  echo "<tr>";
  echo "<td>";
  echo "Name: ".$row3['name']."</br>";
  echo "</td>";
  echo "<td>";
  echo "UIN: ".$row3['uin']."</br>";
  echo "</td>";
  echo "<td>";
  echo "Email: ".$row3['email']."</br>";
  echo "</td>";
  echo "<td>";
  echo "Major: ".$row3['major']."</br>";
  echo "</td>";
  echo "</tr>";
  echo "</table>";
  echo "</br>";
if ($_GET['id'] ==info) {
  echo "Courses interested in:"."</br>";
}
else if ($_GET['id'] == orginfo) {
  echo "Organizations interested in:"."</br>";
}

  echo "</br>";
  echo "</div>";
  
  
  $query4 = "select * from studentcourse where uin=".$_SESSION['uin']."";
  $result4 = mysql_query($query4);
  
  echo "<table border='1' align='center'>";
 			echo "<tr>";
     			//echo "<th>" .ID.    "</th>";
   				echo "<th>" .Course." ".Name.    "</th>";
				echo "<th>" .Course_Code. "</th>";
			 	echo "<th>" .CRN. "</th>";
			 	echo "<th>" .Credits. "</th>";
				echo "<th>" .Start. "</th>";
    			echo "<th>" .End. "</th>";    			
    			echo "<th>" .Days. "</th>";
    			echo "<th>" .Classroom. "</th>";    			
 			    echo "<th>" .Delete. "</th>";
	   		echo "</tr>";
	   		
  while($row4 = mysql_fetch_array($result4))
  	  {
  	   $crn = $row4['crn'] ;  	   
  	   $query5 = "select * from CourseMeetings where CRN=".$crn."";
  	   $result5 = mysql_query($query5);
  	   $row5 = mysql_fetch_array($result5);

  	   $query7 = "select * from Courses where CourseCode="."'".$row5['CourseCode']."'"."";
  	   $result7 = mysql_query($query7);
  	   $row7 = mysql_fetch_array($result7);
  	   
	   /*	 
  	   echo "<ui>";
  	   echo "<li>"."Course name = ".$row5['name']."</li>";
  	   echo "<li>"."Credits = ".$row5['credits']."</li>";
  	   echo "<li>"."Professor = ".$row5['professor']."</li>";
  	   echo "<li>"."CRN = ".$row5['crn']."</li>";
  	   echo "</ui>";
  	   echo "***********************************************"."</br>";
  	   */
  	   
  				//$rows = "select * from studentcourse where uin=".$_SESSION['uin']."";
				//$rows_result = mysql_query($rows);
				//$number_of_rows = mysql_num_rows($rows_result);
				//for ( $i = 0; $i < $number_of_rows; $i++) 
				//{
	   				echo "<tr>";
	   			  		//echo "<td>" .$row5['id']. "</td>";
	   			  		echo "<td>" .$row7['Name']. "</td>";
	   			  		echo "<td>" .$row7['CourseCode']. "</td>";
	   			  		echo "<td>" .$row5['CRN']. "</td>";
	   			  		echo "<td>" .$row7['Credits']. "</td>";
	   			  		echo "<td>" .$row5['Start']. "</td>";
	   			  		echo "<td>" .$row5['End']. "</td>";
   			  			echo "<td>" .$row5['Days']. "</td>";
	   			  		echo "<td>" .$row5['Classroom']. "</td>";
	   			  		echo "<td><center><a href="."profile2.php?crn_delete=".$row5['CRN'].">Delete</a></center>"."</td>";
	   				echo "</tr>";
	   		    //}

  	     	   //echo print_r($row4);
  	  }
  echo "</table>";
  }
}

//$crn_to_delete = $_GET['crn_delete'];
if ($_GET['crn_delete'] !='0')
{
//echo $_SESSION['uin'];
//echo $_GET['crn_delete'];
 $query6 = "DELETE FROM studentcourse WHERE uin=".$_SESSION['uin']." and crn=".$_GET['crn_delete']."";
 //echo $query6;
 $result6 = mysql_query($query6);

}


if ($_GET['id'] ==orginfo)
{
//include("organizations3.php");
  $con = mysql_connect("localhost:3306",'dhe6','dhe6');
                if (!$con)
                {
                die('Could not connect: ' . mysql_error());
                }

  mysql_select_db("dhe6");

  $query3 = "select * from student where uin=".$_SESSION['uin']."";
  $result3 = mysql_query($query3);

  while($row3 = mysql_fetch_array($result3))
  {
  echo "<div align='center'>";
  echo "<table id='mytable'";
  echo "<tr>";
  echo "<td>";
  echo "Name: ".$row3['name']."</br>";
  echo "</td>";
  echo "<td>";
  echo "UIN: ".$row3['uin']."</br>";
  echo "</td>";
  echo "<td>";
  echo "Email: ".$row3['email']."</br>";
  echo "</td>";
  echo "<td>";
  echo "Major: ".$row3['major']."</br>";
  echo "</td>";
  echo "</tr>";
  echo "</table>";
  echo "</br>";
if ($_GET['id']==info) {
  echo "Courses interested in:"."</br>";
}
else if ($_GET['id']==orginfo) {
  echo "Organizations interested in:"."</br>";
}
  echo "</br>";
  echo "</div>";


  $query4 = "select * from studentclub where uin=".$_SESSION['uin']."";
  $result4 = mysql_query($query4);

  echo "<table border='1' align='center'>";
                        echo "<tr>";
                        //echo "<th>" .ID.    "</th>";
                                echo "<th>" .Organization." ".Name.    "</th>";
                                echo "<th>" .Manager_Name. "</th>";
                                echo "<th>" .Manager_Email. "</th>";
                            echo "<th>" .Delete. "</th>";
                        echo "</tr>";

  while($row4 = mysql_fetch_array($result4))
          {
           $email = $row4['clubName'] ;//clubName is actually email address
           $query5 = "select * from Organizations where ManagerEmail='".$email."'"."";
           $result5 = mysql_query($query5);
           $row5 = mysql_fetch_array($result5);


                echo "<tr>";
                        //echo "<td>" .$row5['id']. "</td>";
                        echo "<td>" .$row5['Name']. "</td>";
                        echo "<td>" .$row5['ManagerName']. "</td>";
                        echo "<td>" .$row5['ManagerEmail']. "</td>";
                        echo "<td><center><a href="."profile2.php?org_delete=".$row5['ManagerEmail'].">Delete</a></center>"."</td>";
                echo "</tr>";

          }
  echo "</table>";
  }

}

if ($_GET['org_delete'] !=NULL)
{
 $query6 = "DELETE FROM studentclub WHERE uin=".$_SESSION['uin']." and clubName='".$_GET['org_delete']."'"."";
 $result6 = mysql_query($query6);

}



?>

<br><br><br>

</body>
</html>
