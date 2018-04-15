<?
#include('session.php');
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
if ($_GET['page'] == '') {
header('Location: index.php?page=1');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?=$site_name?></title>
	<? include('meta.php'); ?>
</head>

<body>
	<div class="wrapper">
		<!-- Sidebar Holder -->
		<nav id="sidebar">
				<? include('sidebar.php'); ?>
		</nav>

		<!-- Page Content Holder -->
		<div id="content">
				<? include('navbar.php'); ?>

				<div class="container">
						<div class="row">
								<div class="col-lg-12">
				<? if ($_GET['page'] == '1') {
				echo '<h1 class="page-header">Pets
				<small> </small></h1>';
				}
				else {
				echo '<h1 class="page-header">Pets
				<small>Page '.$_GET['page'].'</small></h1>';
				}
				?>
								</div>
						</div>
						<div class="row">
								<div class="col-md-12">
										<div class="row">
				<?
				$page = $_GET['page'];
				if ($page == '') { $page = 1; }
				$limit = 12;
				$offset = $limit * ($page-1);
				$result = mysqli_query($connection, "SELECT * FROM pets WHERE adopted = 0 ORDER BY petid DESC LIMIT $offset, $limit");
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
												<div class="col-xs-6 col-sm-6 col-lg-3 col-md-3">
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
				?>
										</div>
								</div>
						</div>

				<div class="row">
					<div class="col-lg-12" align="center">
						<nav aria-label="Page navigation">
							<ul class="pagination">
							<?
							$show_first = false;
							$current = $_GET['page'];
							if ($page == '') { $page = 1; }
							$next = $current +1;
							$previous = $current -1;
							if ($previous <= 0)
							{
								$previous = 1;
							}
							$page = $current;
							$start = $page - 2;
							$end = $page + 1;
							if ($start <= 2)
							{
								$start = 1;
								$end = 5;
								$show_first = false;
							}
							if ($i < 12) {
								$end = $current;
							}
							?>
							<?
							if ($current > 1) {
								echo '
								<li>
									<a href="/index.php?page='.$previous.'" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									</a>
								</li>';
							}
							else {
								echo '
								<li class="disabled">
									<a href="#" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									</a>
								</li>';
							}
							while ($start <= $end)
							{
								if ($start == $current)
								{
									if ($start=="1")
									{
										echo '<li class="active"><a href="/index.php">1</a></li>';
									}
									else{
										echo '<li class="active"><a href="/index.php?page='.$current.'">'.$current.'</a></li>';
									}
								}
								else
								{
									if ($start=="1")
									{
										echo '<li><a href="/index.php">1</a></li>';
									}
									else{
										echo '<li><a href="/index.php?page='.$start.'">'.$start.'</a></li>';
									}
								}
								$start ++;
							}
							if ($end != $current) {
								echo '
								<li>
									<a href="/index.php?page='.$next.'" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									</a>
								</li>';
							}
							?>
							</ul>
						</nav>
					</div>
				</div>

				</div>

		</div>
</div>

</body>

</html>
