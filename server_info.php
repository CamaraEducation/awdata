<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Camara School Informaiton</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<body>
	<nav class="navbar navbar-dark bg-dark">
		<div class="container-fluid">
		  	<a class="navbar-brand" href="index.php">
		    	<img src="img/logo.png" width="200" height="70" class="d-inline-block align-top" alt="">
		  	</a>
		  		<span class="navbar-text" style="color: #fff; font-size: 30px;">Camara Education School Server Information</span>
		</div>
	</nav>
</body>

<div class="container" style="padding-top: 20px; width: 600px;">
	<h1>Please fill out the exact school information you are dispatching this server computer.</h1>
<?php  
	$conn = mysqli_connect("localhost", "ccnms_user", "Camara2004!", "portal");
	$sql = "SELECT * FROM `config` WHERE id=1";
	$rs = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($rs);

	if(isset($_POST['update'])){
		$id = $_POST['id'];
		$school=$_POST['school'];
		$school = str_replace(" ", "-", $school);
		$region=$_POST['region'];
		$category=$_POST['category'];
		$ownership=$_POST['ownership'];
		$last=$_POST['last'];
		$update_query = "UPDATE config SET school='$school',category='$category',ownership='$ownership', region='$region' WHERE id=$id";
		// update user data
		$result = mysqli_query($conn, $update_query);

		// Redirect to homepage to display updated user in list
		
		if($result==true)
			{
			    header("Location: success.php");
			}
			else
			{
			    echo ''.mysql_error();
			}
	}
?>
	<form method="POST" action="server_info.php">
	  <div class="form-row">
	    <div class="form-group col-md-12">
	      <label for="school">School Name</label>
	      <input type="text" class="form-control" value="<?php echo $row['school'] ?>" name="school" required>
	    </div>
	    <div class="form-group col-md-12">
	      <label for="region">Region</label>
	      <select name="region" class="form-control" required>
	      	<?php $option = $row['region']; ?>
	        <option>Choose...</option>
	        <option value="Addis Ababa" <?php if($option == "Addis Ababa") echo 'selected = "selected"'; ?>>Addis Ababa</option>
	        <option value="Afar" <?php if($option == "Afar") echo 'selected = "selected"'; ?>>Afar</option>
	        <option value="Amhara" <?php if($option == "Amhara") echo 'selected = "selected"'; ?>>Amhara</option>
	        <option value="Benishangul" <?php if($option == "Benishangul") echo 'selected = "selected"'; ?>>Benishangul-Gumuz</option>
	        <option value="Dire" <?php if($option == "Dire") echo 'selected = "selected"'; ?>>Dire Dawa</option>
	        <option value="Gambela" <?php if($option == "Gambela") echo 'selected = "selected"'; ?>>Gambela</option>
	        <option value="Harari" <?php if($option == "Harari") echo 'selected = "selected"'; ?>>Harari</option>
	        <option value="Oromia" <?php if($option == "Oromia") echo 'selected = "selected"'; ?>>Oromia</option>
	        <option value="Sidama" <?php if($option == "Sidama") echo 'selected = "selected"'; ?>>Sidama</option>
	        <option value="Somali" <?php if($option == "Somali") echo 'selected = "selected"'; ?>>Somali</option>
	        <option value="SWEPR" <?php if($option == "SWEPR") echo 'selected = "selected"'; ?>>South West Ethiopia Peoples' Region(SWEPR)</option>
	        <option value="SNNPR" <?php if($option == "SNNPR") echo 'selected = "selected"'; ?>>Southern Nations, Nationalities, and Peoples' Region(SNNPR)</option>
	        <option value="Tigray" <?php if($option == "Tigray") echo 'selected = "selected"'; ?>>Tigray</option>
	      </select>
	    </div>
	    <div class="form-group col-md-12">
	      <label for="category">School Level</label>
	      <select name="category" class="form-control" required>
	      	<?php $option = $row['category']; ?>
	        <option selected>Choose...</option>
	        <option value="Primary" <?php if($option == "Primary") echo 'selected = "selected"'; ?>>Primary</option>
	        <option value="Secondary" <?php if($option == "Secondary") echo 'selected = "selected"'; ?>>Secondary</option>
	        <option value="Integrated" <?php if($option == "Integrated") echo 'selected = "selected"'; ?>>Integrated</option>
	        <option value="Others" <?php if($option == "Others") echo 'selected = "selected"'; ?>>Others</option>
	      </select>
	    </div>
	    <div class="form-group col-md-12">
	      <label for="ownership">School Ownership</label>
	      <select name="ownership" class="form-control" required>
	      	<?php $option = $row['ownership']; ?>
	        <option selected>Choose...</option>
	        <option value="Government" <?php if($option == "Government") echo 'selected = "selected"'; ?>>Government</option>
	        <option value="Private" <?php if($option == "Private") echo 'selected = "selected"'; ?>>Private</option>
	        <option value="Religious" <?php if($option == "Religious") echo 'selected = "selected"'; ?>>Religious</option>
	        <option value="Community" <?php if($option == "Community") echo 'selected = "selected"'; ?>>Community</option>
	        <option value="Others" <?php if($option == "Others") echo 'selected = "selected"'; ?>>Others</option>
	      </select>
	    </div>
	    <div class="form-group col-md-12">
	      <label for="country">Country</label>
	  	  <input type="text" name="country" class="form-control" readonly value="<?php echo $row['country'] ?>">
	    </div>
	  </div>
	  <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
	  <input type="hidden" name="last" value="1">
	  <button type="submit" name="update" class="btn btn-success">Update School Server information</button>
	</form>
</div>
<script type="text/javascript" src="js/bootstrap.js"></script>
</html>