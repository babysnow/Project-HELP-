<?php session_start(); ?>
<html>
<head>
<title>Course Extractor</title>
<link rel="stylesheet" type="text/css" href="project/style.css" />

</head>

<body>
<?php
  if ($_SESSION['username'] != 'admin'){
    echo "<p>You don't have the right to access this page.</p>";
    echo "<a href='project/index2.php'>Back to main page</a>";
    exit;
  }
?>
  <div id="title"  style="font-family:arial;color:white;font-size:30px;background:#f6822b;right:20px">Username: <?php echo $_SESSION['username'] ?> &nbsp;&nbsp;&nbsp;&nbsp;
  </div>
  <div>
    <img src="project/assets/uiuc_banner.jpg" alt="University of Ilinois" width="120" height="28" style="position: absolute; top:12px; left: 430px"/>
  </div>
  <div><a href='project/index2.php' style="font-family:arial;color:white;font-size:20px;background:#f6822b; position: absolute; top: 14px; left: 561px; text-decoration:none"> CS411 Project </a>
  </div>
  <div style="position: absolute; top: 14px; right: 20px; "> <a href= "mailto:dhe6@illinois.edu" style="font-family:arial;color:white;font-size:20px;background:#f6822b; text-decoration:none">Contact</a>
  </div>

  <div style="position:relative; left:100px">
  <form name="form" action="uiuc_course_list.php" method="get">  
  <h3>Get existing course info from db</h3>
  <h4>Please select your major:</h4>
  <select id="major" name="major">
    <option value="CS">CS</option>
    <option value="ECE">ECE</option>
  </select>
  <h4>Please select the year:</h4>
  <select id="year" name="year">
    <option value="2012">2012</option>
    <option value="2013">2013</option>
    <option value="2011">2011</option>
  </select>
  <h4>Please select the semester:</h4>
  <select id="semester" name="semester">
    <option value="Summer">Summer</option>
    <option value="Spring">Spring</option>
    <option value="Fall">Fall</option>
  </select>
  <br><br>
  <div>
  <input type="submit" value="Get" class="cupid-blue"/>
  </div>
  </form>
  </div>
  
  <div style="position:absolute; right: 100px; top:40px">
  <form name="form_update" action="uiuc_course_list.php" method="post">
  <h3>Update latest course info into db</h3>
  <h4>Please select the major:</h4>
  <select id="major_update" name="major_update">
    <option value="CS">CS</option>
    <option value="ECE">ECE</option>
  </select>
  <h4>Please input the year:</h4>
  <input type="text" id="year_update" name="year_update">
  <h4>Please select the semester:</h4>
  <select id="semester_update" name="semester_update">
    <option value="Summer">Summer</option>
    <option value="Spring">Spring</option>
    <option value="Fall">Fall</option>
  </select>
 <br><br>
  <div>
  <input name="update_btn" type="submit" value="Update" class="cupid-blue"/>
  <input name="delete_btn" type="submit" value="Delete" class="cupid-blue"/>
  </div>
  </form>
  </div>

<br><br>

<?php
 $con = mysql_connect("localhost:3306",'dhe6','dhe6');
 if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

 mysql_select_db("dhe6");

// deal with GET method from html
 if ($_GET['major'] != NULL && $_GET['year'] != NULL && $_GET['semester'] != NULL) {
   $major = $_GET['major'];
   $year = $_GET['year'];
   $semester = $_GET['semester'];
   $term = $semester.' '.$year;
//   $query = "select * from Courses where Term = '" .$term. "' and CourseCode like '" .$major. "%'";
   $query = "select * from Courses, CourseMeetings where Courses.Term = '" .$term. "' and Courses.CourseCode like '" .$major. "%' and CourseMeetings.CourseCode = Courses.CourseCode";
   $result = mysql_query($query);
   $numresult = mysql_num_rows($result);
   if ($numresult == 0) {
     echo "<h4>Results</h4>";
     echo "<p>Sorry, your search for " .$term. " returned none results.</p>";
   }
   else {
     echo "<table align='center' id='mytable'>";
       echo "<tr>";
       echo "<th>" .ID.    "</th>";
       echo "<th>" .Name.    "</th>";
       echo "<th>" .CourseCode. "</th>";
       echo "<th>" .Description. "</th>";
       echo "<th>" .Credits. "</th>";
       echo "<th>" .Term. "</th>";
       echo "<th>" .CRN. "</th>";
       echo "</tr>";

       while($row = mysql_fetch_array($result))
       {
         echo "<tr>";
         echo "<td>" .$row['Id']. "</td>";
         echo "<td>" .$row['Name']. "</td>";
         echo "<td>" .$row['CourseCode']. "</td>";
         echo "<td>" .$row['Descripton']."</td>";
         echo "<td>" .$row['Credits']."</td>";
         echo "<td>" .$row['Term']."</td>";
         echo "<td>" .$row['CRN']."</td>";
         echo "</tr>";
       }
     echo "</table>";  
   }
  }


// deal with POST method from html
  if ($_POST['major_update'] != NULL && $_POST['semester_update'] != NULL && $_POST['year_update'] != NULL && $_POST['update_btn'] == "Update") {
      $major_update = $_POST['major_update'];
      $semester_update = $_POST['semester_update'];
      $year_update = $_POST['year_update'];

      $param1 = $major_update;
      $param2 = $semester_update;
      $param3 = $year_update;

      $command = "python /home/cs411su2012/dhe6/public_html/courseparser.py";
      $command .= " $param1 $param2 $param3 2>&1";

      echo "<pre>";
      $pid = popen($command, "r");
      while (!feof($pid)) {
        echo fread($pid, 256);
      }
      echo "</pre>";

  }

// deal with POST method from html
  if ($_POST['major_update'] != NULL && $_POST['semester_update'] != NULL && $_POST['year_update'] != NULL && $_POST['delete_btn'] == "Delete") {
      $major_update = $_POST['major_update'];
      $semester_update = $_POST['semester_update'];
      $year_update = $_POST['year_update'];

      $term = $semester_update ." ". $year_update;

      $query = "DELETE FROM Courses WHERE Term = '". $term. "' AND CourseCode LIKE '" .$major_update. "%'"; 
      $result = mysql_query($query);
      echo "Done deleting all course info in " .$term. ".";

  }

?>



</body>

</html>

