<?
include('phpheader.php');
session_start();
if(isset($_SESSION['userid']))
{
	$session_userid=$_SESSION['userid'];
	$login=mysqli_query($connection,"SELECT * FROM users WHERE id='$session_userid'");
	$login=mysqli_fetch_assoc($login);
}
if(isset($_COOKIE['petlo']))
{
	$session_userid=$_COOKIE['petlo'];
	$login=mysqli_query($connection,"SELECT * FROM users WHERE password='$session_userid'");
	$login=mysqli_fetch_assoc($login);
	$_SESSION['userid'] = $login['id'];
}
if(session_destroy())
{
	setcookie('petlo', $login['password'], 1);
	header("location: login.php");
}
?>
