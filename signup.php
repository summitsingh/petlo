<?
include('phpheader.php');
session_start();
if(isset($_POST['action']))
{
    if($_POST['action']=="submit")
    {
        $name = mysqli_real_escape_string($connection, $_POST['name']);
        $email = mysqli_real_escape_string($connection, strtolower(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));
        $password = mysqli_real_escape_string($connection, password_hash($_POST['password'], PASSWORD_BCRYPT));
        $type = mysqli_real_escape_string($connection, $_POST['type']);
        $users = mysqli_query($connection, "SELECT email FROM users WHERE email = '$email'");
        $users = mysqli_num_rows($users);
		if(!preg_match("/^[a-zA-Z ]*$/",$name))
		{
			$class = "has-error";
			$message = '<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			Your name is invalid. Only letters and white space allowed.
			</div>';
		}
		elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$class = "has-error";
			$message = '<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			Invalid email address
			</div>';
		}
		elseif($users>=1)
		{
			$class = "has-error";
			$message = '<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			'.$email.' email already exists
			</div>';
		}
		/*elseif(in_array('edu', explode('.', $email)))
		{
			$class = "has-error";
			$message = '<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			.edu email address not allowed
			</div>';
		}*/
		elseif($_POST['password'] != $_POST['password2'])
		{
			$class = "has-error";
			$message = '<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			Both passwords must be same
			</div>';
		}
		else
		{
			mysqli_query($connection,"INSERT INTO users (name, type, email, password) VALUES ('$name', '$type', '$email', '$password')");

			$encrypt = password_hash($email, PASSWORD_BCRYPT);
			$to = $name." <".$email.">";
			$subject = "Account Activation - Petlo";
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
			<strong>Signup sucessfull. Your account activation link has been sent to your email address. Check your email and <a href="login.php">Login Now</a></strong>
			</div>';
		}
    }
}
if(isset($_SESSION['userid'])||isset($_COOKIE['petlo'])){
header("location: profile.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Signup - <?=$site_name?></title>
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
<form class="<? echo $class; ?>" action="signup.php" method="post">
<h2>Sign up</h2>
<div class="row">
<div class="col-xs-12 col-md-6">
<div class="form-group">
<label for="name">Full Name</label>
<input type="name" class="form-control" id="name" name="name" placeholder="Type your full name" value="<?=$_POST['name']?>" required autofocus>
</div>
<div class="form-group">
<label for="type">Type</label>
<select class="form-control" id="type" name="type" required>
<option value="Adopter" <? if($_POST['type']=='Individual') echo 'selected'; elseif($_POST['type']=='') echo 'selected';?>>Individual</option>
<option value="Shelter Home" <? if($_POST['type']=='Shelter Home') echo 'selected';?>>Shelter Home</option>
</select>
</div>
<div class="form-group">
<label for="email">Email address</label>
<input type="email" class="form-control" id="email" name="email" placeholder="Type your email address" value="<?=$_POST['email']?>" required>
</div>
<div class="form-group">
<label for="password">Password</label>
<input type="password" class="form-control" id="password" name="password" placeholder="Type your password" value="<?=$_POST['password']?>" required>
<input type="password" class="form-control" id="password" name="password2" placeholder="Type again" value="<?=$_POST['password2']?>" required>
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
