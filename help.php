<?
include('session.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Help - <?=$site_name?></title>
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
		<h1 class="page-header">Help</h1>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<h4 align="center"><a href="/help/User Guide.pdf">User Manual for Website</a></h4>
		<h4 align="center"><a href="/help/Mobile Guide.pdf">User Manual for Mobile Application</a></h4>
	</div>
</div>

</div>
</div>
</div>
</body>
</html>