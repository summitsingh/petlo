<?
include('session.php');

if ($_GET['petid'] == '') {
header('Location: index.php'); }

$petid = mysqli_real_escape_string($connection, $_GET['petid']);
$pet = mysqli_query($connection,"SELECT * FROM pets WHERE petid = '$petid' LIMIT 1");
$pet = mysqli_fetch_array($pet);

if($login['id']!=$pet['userid']||$pet['adopted']==1)
{
	header('Location: error.php');
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == "submit") {
        foreach ($_FILES["petimage"]["name"] as $key => $error) {
            if (basename($_FILES["petimage"]["name"][$key]) != "") {
                $imageFileType = pathinfo(basename($_FILES["petimage"]["name"][$key]), PATHINFO_EXTENSION);
                $imageFileType = strtolower($imageFileType);
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $message  = '<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					Sorry, only JPG, JPEG, PNG & GIF files are allowed.
					</div>';
                    $uploadOk = 0;
                    $class    = "has-error";
                    break;
                }
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["petimage"]["tmp_name"][$key]);
                if ($check !== false) {
                    //$message = "File is an image - " . $check["mime"] . ".";
                } else {
					$message  = '<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					File is not an image. Sorry, there was an error uploading your file.
					</div>';
                    $uploadOk = 0;
                    $class    = "has-error";
                    break;
                }
            }
        }
        if ($uploadOk !== 0) {
            foreach ($_FILES["petimage"]["name"] as $key => $error) {
                if (basename($_FILES["petimage"]["name"][$key]) != "") {
                    $target_dir  = "uploads/pets/";
                    $newfilename = time() . '_' . rand(100, 999) . '_' . $login['id'] . '.' . pathinfo(basename($_FILES["petimage"]["name"][$key]), PATHINFO_EXTENSION);
					$newfilename = strtolower($newfilename);
                    $target_file = $target_dir . $newfilename;
                    $filename[]  = $newfilename;
                    if (move_uploaded_file($_FILES["petimage"]["tmp_name"][$key], $target_file)) {
                        //$message = "The file ". basename( $_FILES["petimage"]["name"][$key]). " has been uploaded.";
                    }
                }
                /*// Check if file already exists
                if (file_exists($target_file)) {
                $message .= "Sorry, file already exists.";
                $uploadOk = 0;
                }*/
                /*// Check file size
                if ($_FILES["petimage"]["size"][$key] > 500000) {
                $message .= "Sorry, your file is too large.";
                $uploadOk = 0;
                }*/
            }
            $userid = mysqli_real_escape_string($connection, $login['id']);
            $petname = mysqli_real_escape_string($connection, $_POST['petname']);
            $petcategory = mysqli_real_escape_string($connection, $_POST['petcategory']);
			if($filename!='')
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
				$petimage1 = mysqli_real_escape_string($connection, $filename[0]);
				$petimage2 = mysqli_real_escape_string($connection, $filename[1]);
				$petimage3 = mysqli_real_escape_string($connection, $filename[2]);
				$petimage4 = mysqli_real_escape_string($connection, $filename[3]);
				$petimage5 = mysqli_real_escape_string($connection, $filename[4]);
			}
			else
			{
				$petimage1 = $pet['petimage1'];
				$petimage2 = $pet['petimage2'];
				$petimage3 = $pet['petimage3'];
				$petimage4 = $pet['petimage4'];
				$petimage5 = $pet['petimage5'];
			}
						$petgender = mysqli_real_escape_string($connection, $_POST['petgender']);
						$petlocation = mysqli_real_escape_string($connection, $_POST['petlocation']);
						$petbreed = mysqli_real_escape_string($connection, $_POST['petbreed']);
						$pethabitat = mysqli_real_escape_string($connection, $_POST['pethabitat']);
						$petage = mysqli_real_escape_string($connection, $_POST['petage']);
						$petcoat = mysqli_real_escape_string($connection, $_POST['petcoat']);
						$vaccination = mysqli_real_escape_string($connection, $_POST['vaccination']);
            $petinformation = mysqli_real_escape_string($connection, $_POST['petinformation']);
            mysqli_query($connection, "UPDATE pets SET petname='$petname', petcategory='$petcategory', petimage1='$petimage1', petimage2='$petimage2', petimage3='$petimage3', petimage4='$petimage4', petimage5='$petimage5', petgender='$petgender', petlocation='$petlocation', petbreed='$petbreed', pethabitat='$pethabitat', petage='$petage', petcoat='$petcoat', vaccination='$vaccination', petinformation='$petinformation' where petid='$petid'");
			$pet = mysqli_query($connection,"SELECT * FROM pets WHERE petid = '$petid' LIMIT 1");
			$pet = mysqli_fetch_array($pet);
			header('Location: pet.php?petid='.$pet['petid']);
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
        <title>Edit pet - <?=$site_name?></title>
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
            <form class="<? echo $class; ?>" action="editpet.php?petid=<?=$_GET['petid']?>" method="post" enctype="multipart/form-data">
                <legend>Edit a pet</legend>
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
													<div class="row">
														<div class="col-md-8">
															<input type="text" class="form-control" id="petname" name="petname" placeholder="pet Name" value="<? if($_POST['petname']=='') echo $pet['petname']; else echo $_POST['petname'];?>" required autofocus>
														</div>
														<div class="col-md-4">
															<select class="form-control" id="petcategory" name="petcategory" required>
																<option value="Dog" <? if($_POST['petcategory']=='')
																	{if($pet['petcategory']=='Dog')
																	echo 'selected';}
																 else
																	if($_POST['petcategory']=='Dog')
																	echo 'selected';?>>Dog</option>
																<option value="Cat" <? if($_POST['petcategory']=='')
																	{if($pet['petcategory']=='Cat')
																	echo 'selected';}
																 else
																	if($_POST['petcategory']=='Cat')
																	echo 'selected';?>>Cat</option>
																<option value="Bird" <? if($_POST['petcategory']=='')
																	{if($pet['petcategory']=='Bird')
																	echo 'selected';}
																 else
																	if($_POST['petcategory']=='Bird')
																	echo 'selected';?>>Bird</option>
																<option value="Fish" <? if($_POST['petcategory']=='')
																	{if($pet['petcategory']=='Fish')
																	echo 'selected';}
																 else
																	if($_POST['petcategory']=='Fish')
																	echo 'selected';?>>Fish</option>
																<option value="Hamster" <? if($_POST['petcategory']=='')
																	{if($pet['petcategory']=='Hamster')
																	echo 'selected';}
																 else
																	if($_POST['petcategory']=='Hamster')
																	echo 'selected';?>>Hamster</option>
																<option value="Turtle" <? if($_POST['petcategory']=='')
																	{if($pet['petcategory']=='Turtle')
																	echo 'selected';}
																 else
																	if($_POST['petcategory']=='Turtle')
																	echo 'selected';?>>Turtle</option>
																<option value="Other" <? if($_POST['petcategory']=='')
																	{if($pet['petcategory']=='Other')
																	echo 'selected';}
																 else
																	if($_POST['petcategory']=='Other')
																	echo 'selected';?>>Other</option>
															</select>
														</div>
													</div>
                        </div>
												<div class="form-group">
													<div class="row">
														<div class="col-md-12">
															<select class="form-control" id="petgender" name="petgender" required>
																<option value="Male" <? if($_POST['petgender']=='')
																	{if($pet['petgender']=='Male')
																	echo 'selected';}
																 else
																	if($_POST['petgender']=='Male')
																	echo 'selected';?>>Male</option>
																<option value="Female" <? if($_POST['petgender']=='')
																	{if($pet['petgender']=='Female')
																	echo 'selected';}
																 else
																	if($_POST['petgender']=='Female')
																	echo 'selected';?>>Female</option>
															</select>
															<input type="text" class="form-control" id="petname" name="petname" placeholder="Name" value="<? if($_POST['petname']=='') echo $pet['petname']; else echo $_POST['petname'];?>" required autofocus>
															<input type="text" class="form-control" id="petlocation" name="petlocation" placeholder="Location" value="<? if($_POST['petlocation']=='') echo $pet['petlocation']; else echo $_POST['petlocation'];?>" required autofocus>
															<input type="text" class="form-control" id="petbreed" name="petbreed" placeholder="Breed" value="<? if($_POST['petbreed']=='') echo $pet['petbreed']; else echo $_POST['petbreed'];?>" required autofocus>
															<input type="text" class="form-control" id="pethabitat" name="pethabitat" placeholder="Habitat" value="<? if($_POST['pethabitat']=='') echo $pet['pethabitat']; else echo $_POST['pethabitat'];?>" required autofocus>
															<input type="number" class="form-control" id="petage" name="petage" placeholder="Age" value="<? if($_POST['petage']=='') echo $pet['petage']; else echo $_POST['petage'];?>" required autofocus>
															<input type="text" class="form-control" id="petcoat" name="petcoat" placeholder="Type of Coat (optional)" value="<? if($_POST['petcoat']=='') echo $pet['petcoat']; else echo $_POST['petcoat'];?>" autofocus>
														</div>
													</div>
												</div>
                        <div class="form-group">
													<small id="vaccinationhelp" class="form-text text-muted">Vaccinated?</small>
														<div class="radio">
															<label class="radio-inline">
															  <input type="radio" name="vaccination" id="vaccination1" value="Yes"
															  <? if($_POST['vaccination']=='')
																	{if($pet['vaccination']=='Yes')
																	echo 'checked';}
																 else
																	if($_POST['vaccination']=='Yes')
																	echo 'checked';?>
															  > Yes
															</label>
															<label class="radio-inline">
															  <input type="radio" name="vaccination" id="vaccination2" value="No"
															  <? if($_POST['vaccination']=='')
																	{if($pet['vaccination']=='No')
																	echo 'checked';}
																 else
																	if($_POST['vaccination']=='No')
																	echo 'checked';?>
																> No
															</label>
														</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
											<div class="form-group">
												<img class="img-responsive img-thumbnail" src="https://i0.wp.com/<?=$_SERVER["HTTP_HOST"]?>/uploads/pets/<?=$pet['petimage1']?>?w=150&h=150" alt="..." width="150">
												<?
												if($pet['petimage2']!='')
													echo '<img class="img-responsive img-thumbnail" src="https://i0.wp.com/'.$_SERVER["HTTP_HOST"].'/uploads/pets/'.$pet['petimage2'].'?w=150&h=150" alt="..." width="150">';
												if($pet['petimage3']!='')
													echo '<img class="img-responsive img-thumbnail" src="https://i0.wp.com/'.$_SERVER["HTTP_HOST"].'/uploads/pets/'.$pet['petimage3'].'?w=150&h=150" alt="..." width="150">';
												if($pet['petimage4']!='')
													echo '<img class="img-responsive img-thumbnail" src="https://i0.wp.com/'.$_SERVER["HTTP_HOST"].'/uploads/pets/'.$pet['petimage4'].'?w=150&h=150" alt="..." width="150">';
												if($pet['petimage5']!='')
													echo '<img class="img-responsive img-thumbnail" src="https://i0.wp.com/'.$_SERVER["HTTP_HOST"].'/uploads/pets/'.$pet['petimage5'].'?w=150&h=150" alt="..." width="150">';
												?>
											</div>
											<div class="form-group">
                            <label for="petimage">Pet Image</label>
                            <small id="fileHelp" class="form-text text-muted">Uploading new images will remove all old images</small>
                            <input type="file" class="form-control-file" id="petimage" name="petimage[]" aria-describedby="fileHelp">
                            <br>
                            <input type="file" class="form-control-file" id="petimage" name="petimage[]" aria-describedby="fileHelp">
                            <br>
                            <input type="file" class="form-control-file" id="petimage" name="petimage[]" aria-describedby="fileHelp">
                            <br>
                            <input type="file" class="form-control-file" id="petimage" name="petimage[]" aria-describedby="fileHelp">
                            <br>
                            <input type="file" class="form-control-file" id="petimage" name="petimage[]" aria-describedby="fileHelp">
                            <small id="fileHelp" class="form-text text-muted">Only JPG, JPEG, PNG & GIF files are allowed</small>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <textarea class="form-control" id="exampleTextarea" name="petinformation" rows="10" placeholder="Describe your pet... (optional)"><? if($_POST['petinformation']=='') echo $pet['petinformation']; else echo $_POST['petinformation'];?></textarea>
                </div>
                <button type="submit" class="btn btn-primary" name="action" value="submit">Submit</button>
            </form>
        </div>
			</div>
		</div>
    </body>
	</html>
