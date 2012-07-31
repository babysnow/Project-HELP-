<?php

if(isset($_SESSION['uin']))
    $_SESSION['views'] = $_SESSION['views']+ 1;
else
    $_SESSION['views'] = 1;
?>


<html>
<body>
<div align="center">
<img src="assets/login_success.jpg" >
<h3>Login Successful</h3>
</div>
</body>
</html>
