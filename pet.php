<?
include('session.php');

if ($_GET['petid'] == '') {
header('Location: index.php'); }

$petid = mysqli_real_escape_string($connection, $_GET['petid']);
$pet = mysqli_query($connection,"SELECT * FROM pets WHERE petid = '$petid' LIMIT 1");
$pet = mysqli_fetch_array($pet);
$userid = $pet['userid'];
$user = mysqli_query($connection,"SELECT * FROM users WHERE id = '$userid' LIMIT 1");
$user = mysqli_fetch_array($user);

if ($_GET['action'] == "delete")
{
	if($login['id']==$pet['userid']&&$pet['adopted']==0)
	{
		unlink('uploads/pets/'.$pet['petimage1']);
		if($pet['petimage2']!='')
			unlink('uploads/pets/'.$pet['petimage2']);
		if($pet['petimage3']!='')
			unlink('uploads/pets/'.$pet['petimage3']);
		if($pet['petimage4']!='')
			unlink('uploads/pets/'.$pet['petimage4']);
		if($pet['petimage5']!='')
			unlink('uploads/pets/'.$pet['petimage5']);
		mysqli_query($connection, "DELETE FROM pets WHERE petid = '$petid' LIMIT 1");
		header('Location: profile.php?action=petdeleted');
	}
	else
	{
		header('Location: error.php');
	}
}
if ($_GET['action'] == "petadopted")
{
	if($login['id']==$pet['userid']&&$pet['adopted']==0)
	{
		mysqli_query($connection, "UPDATE pets SET adopted='1' where petid='$petid'");
		$pet = mysqli_query($connection,"SELECT * FROM pets WHERE petid = '$petid' LIMIT 1");
		$pet = mysqli_fetch_array($pet);
		$message = '<div class="alert alert-success alert-dismissible" role="alert">
		<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<strong>Thank you so much for using Petlo <3 <a href="index.php">Go to homepage</a></strong>
		</div>';
		$login=mysqli_query($connection,"SELECT * FROM users WHERE id='$session_userid'");
		$login=mysqli_fetch_assoc($login);
	}
	else
	{
		header('Location: error.php');
	}
}
if (isset($_POST['action'])) {
    if ($_POST['action'] == "sendmessage") {
		if($login['contactnumber']=='')
		{
			header("location: profile.php?action=incomplete");
		}
		if($login['displaypicture']=='')
		{
			header("location: displaypicture.php?action=incomplete");
		}
		$to = $user['name'].' <'.$user['email'].'>';
		$subject = $login['name'].' is interested in your product - Petlo';
		$from = "Petlo <summitsingh5@gmail.com>";
		$body='Hi '.$user['name'].',<br><br>'.$login['name'].' is interested in your pet ('.$pet['petname'].') (<a href="https://'.$_SERVER["HTTP_HOST"].'/pet.php?petid='.$pet['petid'].'">https://'.$_SERVER["HTTP_HOST"].'/pet.php?petid='.$pet['petid'].'</a>)<br><br><a href="https://'.$_SERVER["HTTP_HOST"].'/user.php?userid='.$login['id'].'">Click here to contact '.$login['name'].' and send your contact information.</a><br><br>==================================================================<br>Below is the message sent by '.$login['name'].':<br>==================================================================<br><br>'.$_POST['message-text'].'<br><br>==================================================================<br><br>Thank you,<br>Petlo Team<br><br><img src="https://i.imgur.com/5TzNYWs.png" width="200px" alt="Petlo">';
		$headers = "From: " . $from . "\r\n";
		$headers .= "Reply-To: ". $from . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		mail($to,$subject,$body,$headers,'-fsummitsingh5@gmail.com');
		$message = '<div class="alert alert-success" role="alert">
		<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		<strong>Your message has been successfully sent to the seller. Seller will send you contact information if interested. <a href="index.php">Check homepage</a></strong>
		</div>';
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
	<title><?=$pet['petname']?> - <?=$site_name?></title>
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
                <h1 class="page-header"><?=$pet['petname']?>
                    <small><?=$pet['petcategory']?></small>
					<!-- <p class="pull-right text-success bg-success">$<?=$pet['productprice']?></p> -->
					<?if($pet['adopted']==1) echo '<p class="pull-right text-danger bg-danger">(ADOPTED)</p>';?>
                </h1>
            </div>
        </div>

        <div class="row">

            <div class="col-md-8">
				<div id="carousel-images" class="carousel slide" data-ride="carousel">
				  <!-- Indicators -->
				  <ol class="carousel-indicators">
					<li data-target="#carousel-images" data-slide-to="0" class="active"></li>
					<?
					if($pet['petimage2']!='')
						echo '<li data-target="#carousel-images" data-slide-to="1"></li>';
					if($pet['petimage3']!='')
						echo '<li data-target="#carousel-images" data-slide-to="2"></li>';
					if($pet['petimage4']!='')
						echo '<li data-target="#carousel-images" data-slide-to="3"></li>';
					if($pet['petimage5']!='')
						echo '<li data-target="#carousel-images" data-slide-to="4"></li>';
					?>
				  </ol>

				  <!-- Wrapper for slides -->
				  <div class="carousel-inner" role="listbox">
					<div class="item active">
					  <img style="margin: auto;" src="https://i0.wp.com/<?=$_SERVER["HTTP_HOST"]?>/uploads/pets/<?=$pet['petimage1']?>?w=500&h=500" alt="...">
					</div>
					<?
					if($pet['petimage2']!='')
						echo '
					<div class="item">
					  <img style="margin: auto;" src="https://i0.wp.com/'.$_SERVER["HTTP_HOST"].'/uploads/pets/'.$pet['petimage2'].'?w=500&h=500" alt="...">
					</div>';
					if($pet['petimage3']!='')
						echo '
					<div class="item">
					  <img style="margin: auto;" src="https://i0.wp.com/'.$_SERVER["HTTP_HOST"].'/uploads/pets/'.$pet['petimage3'].'?w=500&h=500" alt="...">
					</div>';
					if($pet['petimage4']!='')
						echo '
					<div class="item">
					  <img style="margin: auto;" src="https://i0.wp.com/'.$_SERVER["HTTP_HOST"].'/uploads/pets/'.$pet['petimage4'].'?w=500&h=500" alt="...">
					</div>';
					if($pet['petimage5']!='')
						echo '
					<div class="item">
					  <img style="margin: auto;" src="https://i0.wp.com/'.$_SERVER["HTTP_HOST"].'/uploads/pets/'.$pet['petimage5'].'?w=500&h=500" alt="...">
					</div>';
					?>
				  </div>

				  <!-- Controls -->
				  <a class="left carousel-control" href="#carousel-images" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				  </a>
				  <a class="right carousel-control" href="#carousel-images" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				  </a>
				</div>
				<br><br>
				<iframe width="100%" height="300" frameborder="1" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBjM5iPN6jG1uBYvid24MnheMVr93eQlxc&q=<?=$pet['petlocation']?>" allowfullscreen></iframe>

            </div>

						<div class="col-md-4">
							<h3>Pet Information:</h3>
											<ul>
													<li>Gender: <b><?=$pet['petgender']?></b></li>
													<li>Location: <b><?=$pet['petlocation']?></b></li>
													<li>Breed: <b><?=$pet['petbreed']?></b></li>
													<li>Habitat: <b><?=$pet['pethabitat']?></b></li>
													<li>Age: <b><?=$pet['petage']?></b></li>
													<li>Type of Coat: <b><?=$pet['petcoat']?></b></li>
													<li>Vaccination: <b><?=$pet['vaccination']?></b></li>
													<li>Additional Info: <b><?=$pet['petinformation']?></b></li>
													<li>Date Added: <b><?=date_format(date_create($pet['date']),"d M Y")?></b></li>
											</ul>
							<h3>Owner Information:</h3>
			                <ul>
			                    <li>Owner Name: <a href="/user.php?userid=<?=$user['id']?>"><b><?=$user['name']?></b></a></li>
			                    <li>Location: <b><?=$user['address3']?></b></li>
			                </ul>
				<?
				if($login['id']==$pet['userid']&&$pet['adopted']==0)
				{
					echo '
					<div class="btn-group">
						<a href="/editpet.php?petid='.$pet['petid'].'" class="btn btn-warning">Edit Pet</a>
					</div>
					<div class="btn-group">
						<a href="?petid='.$pet['petid'].'&action=delete" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete?\')">Delete Pet</a>
					</div>
					<div class="btn-group">
						<a href="?petid='.$pet['petid'].'&action=petadopted" class="btn btn-success" onclick="return confirm(\'Are you sure you want to mark this pet as adopted? (marking this pet as adopted will remove it from active listings)\')">Mark as Adopted</a>
					</div>
					';
				}
				?>
				<?
				if($login['id']!=$user['id'])
				{
				?>
				<div class="btn-group">
					<a href="/user.php?userid=<?=$user['id']?>" class="btn btn-success">View <?=$user['name']?>'s Profile</a>
				</div>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Contact Owner</button>
				<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
				  <div class="modal-dialog" role="document">
					<div class="modal-content">
					<form action="" method="post">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="exampleModalLabel">New message</h4>
					  </div>
					  <div class="modal-body">
						  <div class="form-group">
							<label for="recipient-name" class="control-label">Recipient:</label>
							<input type="text" class="form-control" id="recipient-name" value="<?=$user['name']?>" disabled>
						  </div>
						  <div class="form-group">
							<label for="message-text" class="control-label">Message:</label>
							<textarea class="form-control" id="message-text" name="message-text" rows="10">I'm interested in this pet. Please send me your contact information.</textarea>
						  </div>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" name="action" value="sendmessage">Send message</button>
					  </div>
					</form>
					</div>
				  </div>
				</div>
				<?
				}
				?>
            </div>
        </div>

				<div class="row">

						<div class="col-lg-12">
								<h3 class="page-header">More...</h3>
						</div>

						<div class="col-md-12">
								<div class="row">
		<?
		$page = $_GET['page'];
		if ($page == '') { $page = 1; }
		$limit = 6;
		$offset = $limit * ($page-1);
		$result = mysqli_query($connection, "SELECT * FROM pets WHERE petcategory like '%".mysqli_real_escape_string($connection, $pet['petcategory'])."%' AND adopted = 0 AND petid != $petid ORDER BY petid DESC LIMIT $offset, $limit");
		$i = 0;
		$r = 0;
		while ($row = mysqli_fetch_array($result))
		{
			//if ($r==3) { echo '</tr><tr style="height:270px">'; $r = 0; }
			$i ++;
			$r ++;
			$d_name = $row['petname'];

			if (strlen($d_name) > 30)
				$d_name = substr($d_name, 0, 27) . '...';
		?>
										<div class="col-xs-12 col-sm-12 col-lg-4 col-md-4">
												<div class="thumbnail" style="height:250px;">
														<a href="/pet.php?petid=<?=$row['petid']?>" title="<?=$row['petname']?>"><img src="https://i0.wp.com/<?=$_SERVER["HTTP_HOST"]?>/uploads/pets/<?=$row['petimage1']?>?w=320&h=150px" alt="">
														<div class="caption">
																<small class="text-success bg-success"><?=$row['petbreed']?></small>
																<h4><?=$d_name?></a>
																</h4>
																<small><?=$row['petgender']?></small>
																<small><a href="/category.php?category=<?=$row['petcategory']?>"><?=$row['petcategory']?></a></small>
														</div>
												</div>
										</div>
		<?
		}
		if ($i==0)
			echo '<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			No animals found...
			</div>';
		?>
								</div>
						</div>

				</div>

    </div>

	</div>
</div>

</body>

</html>
