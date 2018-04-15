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
	$cookie=$_COOKIE['petlo'];
	$login=mysqli_query($connection,"SELECT * FROM users WHERE password='$cookie'");
	$login=mysqli_fetch_assoc($login);
	$_SESSION['userid'] = $login['id'];
}
if(isset($_GET['action']))
{
    if($_GET['action']=="reset")
    {
        $encrypt = mysqli_real_escape_string($connection, $_GET['encrypt']);
        $email = mysqli_real_escape_string($connection, $_GET['email']);
        $users = mysqli_query($connection, "SELECT * FROM users WHERE email='$email'");
        $users = mysqli_fetch_array($users);
        if(count($users)>=1)
        {
			if (password_verify($users['id'], $encrypt))
			{
				$_SESSION['userid'] = $users['id'];
				header("location: password.php");
			}
			else
			{
				header("location: forgotpassword.php?action=error");
			}
        }
		else
		{
			header("location: forgotpassword.php?action=error");
		}
    }
}
if(isset($_POST['action']))
{
	if($_POST['action']=="submit")
	{
		$email = mysqli_real_escape_string($connection, $_POST['email']);
		$users = mysqli_query($connection, "SELECT * FROM users WHERE email='$email'");
		$users = mysqli_fetch_array($users);
		if($users>=1)
		{
			$encrypt = password_hash($users['id'], PASSWORD_BCRYPT);
			$to = $users['name']." <".$email.">";
			$subject="Forgot Password - Petlo";
			$from = "Petlo <summitsingh5@gmail.com>";
			$body='Hi '.$users['name'].',<br><br>Click the link to reset your password <a href="https://'.$_SERVER["HTTP_HOST"].'/forgotpassword.php?email='.$email.'&encrypt='.$encrypt.'&action=reset">https://'.$_SERVER["HTTP_HOST"].'/forgotpassword.php?email='.$email.'&encrypt='.$encrypt.'&action=reset</a><br><br>Thank you,<br>Petlo Team<br><br>
			<img src="https://i.imgur.com/5TzNYWs.png" width="200px" alt="Petlo">';
			$headers = "From: " . $from . "\r\n";
			$headers .= "Reply-To: ". $from . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			mail($to,$subject,$body,$headers,'-fsummitsingh5@gmail.com');

			$class = "has-success";
			$message = '<div class="alert alert-success" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<strong>Your password reset link has been send to your email address. Check your email and <a href="login.php">Login Now</a></strong>
			</div>';
		}
        else
        {
			$class = "has-error";
            $message = '<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			The email Address you entered does not belong to any account. Make sure that it is typed correctly.
			</div>';
        }
	}
}
if ($_GET['action'] == "error")
{
	$class = "has-error";
	$message = '<div class="alert alert-danger" role="alert">
	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	Invalid key, please try again
	</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Forgot Password - <?=$site_name?></title>
<? include('meta.php'); ?>
</head>
<body>
<div class="wrapper">
<nav id="sidebar">
<? include('sidebar.php'); ?>
</nav>
<div id="content">
<? include('navbar.php'); ?>
<div class="container">
<? echo $message; ?>
<form class="<? echo $class; ?>" action="forgotpassword.php" method="post">
<h2>Forgot Password</h2>
<div class="row">
<div class="col-xs-12 col-md-6">
<div class="form-group">
<label for="inputEmail">Email address</label>
<input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email" autofocus required>
</div>
</div>
</div>
<button type="submit" class="btn btn-primary" name="action" value="submit">Submit</button>
</form>
</div>
</div>
</div>
</body>
</html>
