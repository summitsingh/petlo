<?
include('phpheader.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Error - <?=$site_name?></title>
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
<h2>Error</h2>
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <strong>Sorry, the page you have requested is not available.</strong>
</div>
</div>
</div>
</div>
</body>
</html>