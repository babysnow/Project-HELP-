<html>
<head>
<title>User Registration</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<form action="register2.php" method="post">
<div id="title"  style="font-family:arial;color:white;font-size:30px;background:#f6822b;right:20px">Username: <?php echo $_SESSION['username'] ?> &nbsp;&nbsp;&nbsp;&nbsp;
</div>
<div>
<img src="assets/uiuc_banner.jpg" alt="University of Ilinois" width="120" height="28" style="position: absolute; top:12px; left: 430px"/>
</div>
<div><a href='index2.php' style="font-family:arial;color:white;font-size:20px;background:#f6822b; position: absolute; top: 14px; left: 561px; text-decoration:none"> CS411 Project </a>
</div>
<div style="position: absolute; top: 14px; right: 20px; "> <a href= "mailto:aggarwa3@illinois.edu" style="font-family:arial;color:white;font-size:20px;background:#f6822b; text-decoration:none">Contact</a>
</div>

<br><br>
Please input the registration details to create an account: <br><br>
<table class="table1" border="1" id="mytable">
	<tr>
		<td>First Name :</td><td><input name="firstname" type="text" size"20"></input></td>
	</tr>
	<tr>
		<td>Username :</td><td><input name="username" type="text" size"20"></input></td>
	</tr>
	<tr>
		<td>Email :</td><td><input name="regemail" type="text" size"20"></input></td>
	</tr>
	<tr>
		<td>Major (ECE/CS) :</td><td><input name="major" type="text" size"20"></input></td>
	</tr>
	<tr>
		<td>UIN :</td><td><input name="uin1" type="text" size"20"></input></td>
	</tr>
	<tr>
		<td>Retype UIN :</td><td><input name="uin2" type="text" size"20"></input></td>
	</tr>
</table>
<br>
<input type="submit" value="Register me!" class="cupid-blue"/>
</br></br></br></br></br>
</form>
</body>
</html>
