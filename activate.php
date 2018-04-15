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
    if($_GET['action']=="activate")
    {
        $encrypt = mysqli_real_escape_string($connection, $_GET['encrypt']);
        $email = mysqli_real_escape_string($connection, $_GET['email']);
        $users = mysqli_query($connection, "SELECT * FROM users WHERE email='$email'");
        $users = mysqli_fetch_array($users);
        if(count($users)>=1)
        {
			if (password_verify($users['email'], $encrypt))
			{
				mysqli_query($connection, "UPDATE users SET status='1' where email='$email'");
				header("location: activate.php?action=success");
			}
			else
			{
				header("location: activate.php?action=error");
			}
        }
		else
		{
			header("location: activate.php?action=error");
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
			if($users['status']=='1')
			{
				$class = "has-success";
				$message = '<div class="alert alert-success" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<strong>Your account is already activated. <a href="login.php">Login Now</a></strong>
				</div>';
			}
			else
			{
				$encrypt = password_hash($email, PASSWORD_BCRYPT);
				$to = $users['name']." <".$email.">";
				$subject="Account Activation - Petlo";
				$from = "Petlo <summitsingh5@gmail.com>";
				$body='Hi '.$users['name'].',<br><br>Click the link to activate your account <a href="https://'.$_SERVER["HTTP_HOST"].'/activate.php?email='.$email.'&encrypt='.$encrypt.'&action=activate">https://'.$_SERVER["HTTP_HOST"].'/activate.php?email='.$email.'&encrypt='.$encrypt.'&action=activate</a><br><br>Thank you,<br>Petlo Team<br><br><img src="https://i.imgur.com/5TzNYWs.png" width="200px" alt="Petlo">';
				$headers = "From: " . $from . "\r\n";
				$headers .= "Reply-To: ". $from . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				mail($to,$subject,$body,$headers,'-fsummitsingh5@gmail.com');

				$class = "has-success";
				$message = '<div class="alert alert-success" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<strong>Your account activation link has been send to your email address! Check your email and <a href="login.php">Login Now</a></strong>
				</div>';
			}
		}
        else
        {
			$class = "has-error";
            $message = '<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			Invalid email address
			</div>';
        }
	}
}
if ($_GET['action'] == "success")
{
	$class = "has-success";
	$message = '<div class="alert alert-success" role="alert">
	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	<strong>Account activated successfully! <a href="login.php">Login Now</a></strong>
	</div>';
}
if ($_GET['action'] == "error")
{
	$class = "has-error";
	$message = '<div class="alert alert-danger" role="alert">
	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	Invalid key, please try again
	</div>';
}
if ($_GET['status'] == "0")
{
	$class = "has-error";
	$message = '<div class="alert alert-error" role="alert">
	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	<strong>Your account is not activated. Your account activation link was send to your email address! Check your email to activate your account. You can type your email address above to resend email.</strong>
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
<title>Account Activation - <?=$site_name?></title>
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
<form class="<? echo $class; ?>" action="activate.php" method="post">
<h2>Account Activation</h2>
<div class="row">
<div class="col-xs-12 col-md-6">
<div class="form-group">
<label for="inputEmail">Email address</label>
<input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email" value="<?=$_GET['email']?>" required>
</div>
</div>
</div>
<button type="submit" class="btn btn-primary" name="action" value="submit" <?if($users['status']=='1') echo disabled;?>>Submit</button>
</form>
</div>
</div>
</div>
</body>
</html>
