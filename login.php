<?
include('phpheader.php');
session_start();
if(isset($_POST['action']))
{
    if($_POST['action']=="submit")
    {
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $password = mysqli_real_escape_string($connection, $_POST['password']);
        $users = mysqli_query($connection, "SELECT * FROM users WHERE email='$email'");
        $users = mysqli_fetch_array($users);
        if(count($users)>=1)
        {
			if (password_verify($_POST['password'], $users['password']))
			{
				//Password matches, so create the session
				$_SESSION['userid'] = $users['id']; // Initializing Session
				if ($_POST['remember-me']=='remember-me')
				{
					$hour = time() + 3600;
					setcookie('petlo', $users['password'], $hour);
					$year = time() + 31536000;
					setcookie('petlo_remember_me', $_POST['email'], $year);
				}
				else
				{
					setcookie('petlo_remember_me', $_POST['email'], 1);
				}
			}
			else
			{
				$class = "has-error";
				$message = '<br>
				<div class="alert alert-danger" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				The password you entered is incorrect. Please try again (make sure your caps lock is on/off).
				</div>';
			}
        }
        else
        {
			$class = "has-error";
            $message = '<br>
			<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			The email address you entered does not belong to any account. Make sure that it is typed correctly.
			</div>';
        }
    }
}
if(isset($_SESSION['userid'])||isset($_COOKIE['petlo'])){
	if($_POST['location'] != '')
		header("location: ".$_POST['location']);
	else
		header("location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Login - <?=$site_name?></title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
<style>
@import "https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700";
body {
font-family: 'Montserrat', sans-serif;
padding-top: 40px;
padding-bottom: 40px;
background-color: #eee;
}
.form-signin {
max-width: 330px;
padding: 15px;
margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
margin-bottom: 10px;
}
.form-signin .checkbox {
font-weight: normal;
}
.form-signin .form-control {
position: relative;
height: auto;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
padding: 10px;
font-size: 16px;
}
.form-signin .form-control:focus {
z-index: 2;
}
.form-signin input[type="email"] {
margin-bottom: -1px;
border-bottom-right-radius: 0;
border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
margin-bottom: 10px;
border-top-left-radius: 0;
border-top-right-radius: 0;
}
</style>
</head>
<body>
<div class="container">
<form class="form-signin <? echo $class; ?>" action="login.php" method="post">
<br><br>
<a href="/"><img src="https://i.imgur.com/5TzNYWs.png" width="100%" alt="Pelto"></a>
<br><br><br><br>
<div class="row">
<div class="col-xs-1">
<i class="fas fa-user" style="padding-top: 15px"></i>
</div>
<div class="col-xs-10">
<label for="inputEmail" class="sr-only">Email address</label>
 <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" value="<? if(isset($_COOKIE['petlo_remember_me'])) echo $_COOKIE['petlo_remember_me']; else echo $_POST['email'];?>" required autofocus>
</div>
</div>
<div class="row">
<div class="col-xs-1">
<i class="fas fa-key" style="padding-top: 15px"></i>
</div>
<div class="col-xs-10">
<label for="inputPassword" class="sr-only">Password</label>
<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
</div>
</div>
<div class="checkbox">
<label>
<input type="checkbox" name="remember-me" value="remember-me" <? if(isset($_COOKIE['petlo_remember_me'])) echo 'checked';?>> Remember me
</label>
</div>
<button class="btn btn-lg btn-danger btn-block" type="submit" name="action" value="submit">Sign in</button>
<? echo $message; ?>
<br><br>
<center>
<b>Don't have an account? <a href="signup.php" style="color: #ec4646;">Sign Up</a></b>
<br><br>
<b><a href="forgotpassword.php" style="color: #c0c0c0;">Forgot Password?</a></b>
</center>
<input type="hidden" name="location" value="<? if(isset($_GET['location'])) echo htmlspecialchars($_GET['location']); echo $_POST['location']; ?>">
</form>
</div>
</body>
</html>
