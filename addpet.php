<?
include('session.php');
if($login['contactnumber']=='')
{
	header("location: profile.php?action=incomplete");
}
if($login['displaypicture']=='')
{
	header("location: displaypicture.php?action=incomplete");
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
            $petimage1 = mysqli_real_escape_string($connection, $filename[0]);
            $petimage2 = mysqli_real_escape_string($connection, $filename[1]);
            $petimage3 = mysqli_real_escape_string($connection, $filename[2]);
            $petimage4 = mysqli_real_escape_string($connection, $filename[3]);
            $petimage5 = mysqli_real_escape_string($connection, $filename[4]);
						$petgender = mysqli_real_escape_string($connection, $_POST['petgender']);
						$petlocation = mysqli_real_escape_string($connection, $_POST['petlocation']);
						$petbreed = mysqli_real_escape_string($connection, $_POST['petbreed']);
						$pethabitat = mysqli_real_escape_string($connection, $_POST['pethabitat']);
						$petage = mysqli_real_escape_string($connection, $_POST['petage']);
						$petcoat = mysqli_real_escape_string($connection, $_POST['petcoat']);
						$vaccination = mysqli_real_escape_string($connection, $_POST['vaccination']);
						$petinformation = mysqli_real_escape_string($connection, $_POST['petinformation']);
            mysqli_query($connection, "INSERT INTO pets (userid, petname, petcategory, petimage1, petimage2, petimage3, petimage4, petimage5, petgender, petlocation, petbreed, pethabitat, petage, petcoat, vaccination, petinformation) VALUES ('$userid', '$petname', '$petcategory', '$petimage1', '$petimage2', '$petimage3', '$petimage4', '$petimage5', '$petgender', '$petlocation', '$petbreed', '$pethabitat', '$petage', '$petcoat', '$vaccination', '$petinformation')");
            $pet = mysqli_query($connection, "SELECT petid FROM pets WHERE petimage1='$petimage1'");
            $pet = mysqli_fetch_assoc($pet);
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
        <title>Add a pet - <?=$site_name?></title>
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
            <form class="<? echo $class; ?>" action="addpet.php" method="post" enctype="multipart/form-data">
                <legend>Add a pet</legend>
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
													<div class="row">
														<div class="col-md-8">
															<input type="text" class="form-control" id="petname" name="petname" placeholder="Name" value="<?=$_POST['petname']?>" required autofocus>
														</div>
														<div class="col-md-4">
															<select class="form-control" id="petcategory" name="petcategory" required>
																<option value="Dog" <? if($_POST['petcategory']=='Dog') echo 'selected'; elseif($_POST['petcategory']=='') echo 'selected';?>>Dog</option>
																<option value="Cat" <? if($_POST['petcategory']=='Cat') echo 'selected';?>>Cat</option>
																<option value="Bird" <? if($_POST['petcategory']=='Bird') echo 'selected';?>>Bird</option>
																<option value="Fish" <? if($_POST['petcategory']=='Fish') echo 'selected';?>>Fish</option>
																<option value="Hamster" <? if($_POST['petcategory']=='Hamster') echo 'selected';?>>Hamster</option>
																<option value="Turtle" <? if($_POST['petcategory']=='Turtle') echo 'selected';?>>Turtle</option>
																<option value="Other" <? if($_POST['petcategory']=='Other') echo 'selected';?>>Other</option>
															</select>
														</div>
													</div>
                        </div>
												<div class="form-group">
													<div class="row">
														<div class="col-md-12">
															<select class="form-control" id="petgender" name="petgender" required>
																<option value="Male" <? if($_POST['petgender']=='Male') echo 'selected'; elseif($_POST['petgender']=='') echo 'selected';?>>Male</option>
																<option value="Female" <? if($_POST['petgender']=='Female') echo 'selected';?>>Female</option>
															</select>
															<input type="text" class="form-control" id="petlocation" name="petlocation" placeholder="Location" value="<? if($_POST['petlocation']=='') echo 'Daskalakis Athletic Center, Philadelphia, PA'; else echo $_POST['petlocation']; ?>" required autofocus>
															<input type="text" class="form-control" id="petbreed" name="petbreed" placeholder="Breed" value="<?=$_POST['petbreed']?>" required autofocus>
															<input type="text" class="form-control" id="pethabitat" name="pethabitat" placeholder="Habitat" value="<?=$_POST['pethabitat']?>" required autofocus>
															<input type="number" class="form-control" id="petage" name="petage" placeholder="Age" value="<?=$_POST['petage']?>" required autofocus>
															<input type="text" class="form-control" id="petcoat" name="petcoat" placeholder="Type of Coat (optional)" value="<?=$_POST['petcoat']?>" autofocus>
														</div>
													</div>
												</div>
                        <div class="form-group">
													<small id="vaccinationhelp" class="form-text text-muted">Vaccinated?</small>
														<div class="radio">
															<label class="radio-inline">
															  <input type="radio" name="vaccination" id="vaccination1" value="Yes" <? if($_POST['vaccination']=='Yes') echo 'checked'; elseif($_POST['vaccination']=='') echo 'checked';?>> Yes
															</label>
															<label class="radio-inline">
															  <input type="radio" name="vaccination" id="vaccination2" value="No" <? if($_POST['vaccination']=='No') echo 'checked';?>> No
															</label>
														</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="petimage">Image</label>
                            <input type="file" class="form-control-file" id="petimage" name="petimage[]" aria-describedby="fileHelp" required>
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
                    <textarea class="form-control" id="exampleTextarea" name="petinformation" rows="10" placeholder="Describe your pet... (optional)"><?=$_POST['petinformation']?></textarea>
                </div>
                <button type="submit" class="btn btn-primary" name="action" value="submit">Submit</button>
            </form>
        </div>
			</div>
			</div>
    </body>
	</html>
