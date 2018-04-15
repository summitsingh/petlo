<?
include('session.php');
if (isset($_POST['action']))
{
    if ($_POST['action'] == "submit")
	{
		$name = mysqli_real_escape_string($connection, $_POST['name']);
		$email = mysqli_real_escape_string($connection, $_POST['email']);
		$subject = mysqli_real_escape_string($connection, $_POST['subject']);
		$bodycontent = mysqli_real_escape_string($connection, $_POST['bodycontent']);
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
		else
		{
			$to = "Petlo <summitsingh5@gmail.com>";
			$subject = 'Contact Us Form - '.$subject.' - Petlo';
			$from = $name.' <'.$email.'>';
			$body='Hi Admin,<br><br>==================================================================<br>Below is the message sent by '.$name.':<br>==================================================================<br><br>'.$bodycontent.'<br><br>==================================================================<br><br>Thank you,<br>Petlo Team<br><br><img src="https://i.imgur.com/5TzNYWs.png" width="200px" alt="Petlo">';
			$headers = "From: " . $from . "\r\n";
			$headers .= "Reply-To: ". $from . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			mail($to,$subject,$body,$headers,'-f'.$email);
			$class = "has-success";
			$message = '<div class="alert alert-success alert-dismissible" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>Your message has been sent successfully. We will reply back to you in 24 hours. Thank you for your patience.</strong>
			</div>';
		}
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Contact Us - <?=$site_name?></title>
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
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Contact Us</h1>
		<h4 align="center">Your feedback is appreciated.</h4>
	</div>
</div>
<form class="<? echo $class; ?>" action="" method="post" enctype="multipart/form-data">
<div class="row">
<div class="col-lg-12">
<div class="form-group">
<label for="name">Full Name</label>
<input type="text" class="form-control" id="name" name="name" placeholder="Type your full name" value="<?=$_POST['name']?>" required autofocus>
</div>
<div class="form-group">
<label for="email">Email address</label>
<input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?=$_POST['email']?>" required>
</div>
<div class="form-group">
<label for="name">Query Subject</label>
<input type="text" class="form-control" id="subject" name="subject" placeholder="Query Subject" value="<?=$_POST['subject']?>" required>
</div>
<div class="form-group">
<textarea class="form-control" id="bodycontent" name="bodycontent" rows="10" placeholder="Include your message here..." required><?=$_POST['bodycontent']?></textarea>
</div>
<button type="submit" class="btn btn-primary" name="action" value="submit">Submit</button>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
