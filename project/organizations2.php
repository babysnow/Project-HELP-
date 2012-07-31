<html>
<head>
<title>Add organization</title>
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

<form action="registerOrganization.php" method="post">
<br><br>
Fill out the details of the Organization you are interested in: <br><br>
<table border="2" id="mytable">
	<tr>
		<td>Name of Organization:</td><td><input name="name" type="text" size"30"></input></td>
	</tr>

	<tr>
		<td>Description :</td><td><input name="description" type="text" size"500"></input></td>
	</tr>

	<tr>
		<td>Manager's Name  :</td><td><input name="manager_name" type="text" size"30"></input></td>
	</tr>

	<tr>
		<td>Manager's Email :</td><td><input name="manager_email" type="text" size"30"></input></td>
	</tr>

</table>
<br>
<input type="submit" value="Add" class="cupid-blue"/>
</br></br></br></br></br>

</form>
</body>
</html>
