<?php 
session_start();
if(session_destroy())
{
echo "<title>Log Out Successfully</title>";
echo "<div align='center'>";
echo "<img src='assets/login_success.jpg'>";
echo "<h2>You have logged out successfully.</h2>";
echo "<h3><a href='index2.php'>back to main page</a></h3>";
echo "</div>";
}
?>
