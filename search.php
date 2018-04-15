<?
include('session.php');
if ($_GET['q'] == '') {
header('Location: index.php'); }
if ($_GET['page'] == '') {
header('Location: search.php?q='.$_GET['q'].'&page=1');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Search results for <?=$_GET['q']?> - <?=$site_name?></title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
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
        <div class="row">
            <div class="col-lg-12">
		<? if ($_GET['page'] == '1') {
		echo '<h1 class="page-header">Search results for '.$_GET['q'].'
		<small>Recent</small></h1>';
		}
		else {
		echo '<h1 class="page-header">Search results for '.$_GET['q'].'
		<small>Page '.$_GET['page'].'</small></h1>';
		}
		?>
            </div>
        </div>
        <div class="row">

            <div class="col-md-12">
                <div class="row">
		<?
		if ($_GET['q'] != '') { $_POST['q'] = $_GET['q']; }
		$page = $_GET['page'];
		if ($page == '') { $page = 1; }
		$limit = 12;
		$offset = $limit * ($page-1);
		$_GET['q'] = strtolower($_GET['q']);
		$_GET['q'] = strip_tags($_GET['q']);
		$_GET['q'] = trim ($_GET['q']);
		$result = mysqli_query($connection, "SELECT * FROM pets WHERE petname like '%".strtolower(mysqli_real_escape_string($connection, $_POST['q']))."%' OR petbreed like '%".strtolower(mysqli_real_escape_string($connection, $_POST['q']))."%' OR pethabitat like '%".strtolower(mysqli_real_escape_string($connection, $_POST['q']))."%' OR petcoat like '%".strtolower(mysqli_real_escape_string($connection, $_POST['q']))."%' OR petinformation like '%".strtolower(mysqli_real_escape_string($connection, $_POST['q']))."%' OR petage like '%".strtolower(mysqli_real_escape_string($connection, $_POST['q']))."%' OR petlocation like '%".strtolower(mysqli_real_escape_string($connection, $_POST['q']))."%' OR petgender like '%".strtolower(mysqli_real_escape_string($connection, $_POST['q']))."%' OR petcategory like '%".strtolower(mysqli_real_escape_string($connection, $_POST['q']))."%' ORDER BY petid DESC LIMIT $offset, $limit");
		$i = 0;
		$r = 0;
		while ($row = mysqli_fetch_array($result))
		{
			if($row['adopted']==0)
			{
			//if ($r==3) { echo '</tr><tr style="height:270px">'; $r = 0; }
			$i ++;
			$r ++;
			$d_name = $row['petname'];

			if (strlen($d_name) > 30)
				$d_name = substr($d_name, 0, 27) . '...';
		?>
                    <div class="col-xs-6 col-sm-6 col-lg-3 col-md-3">
                        <div class="thumbnail">
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
		}
		if ($i==0)
			echo '<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			Your search <b>"'.$_GET['q'].'"</b> did not match any pets
			</div>';
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
