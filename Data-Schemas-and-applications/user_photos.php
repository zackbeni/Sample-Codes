<?php
// NOTE:
// Image upload may fail due to 
// "Got a packet bigger than 'max_allowed_packet' bytes"
//
// You need to change your webservers my.cnf or my.ini file at the line "max_allowed_packet=100M".


// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
	$target_dir = "uploads/user/";
	// $target_file = $target_dir . basename($_FILES["imagefile"]["name"]);
	$target_file = $target_dir . basename($_FILES["imagefile"]["name"]);
	$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

	$target_imagename = uniqid('', true) . "." . $imageFileType;
	$target_file = $target_dir . $target_imagename;

	$uploadOk = 1;


	$check = getimagesize($_FILES["imagefile"]["tmp_name"]);
	if ($check !== false) {
		echo "File is an image - " . $check["mime"] . ".";
		$uploadOk = 1;
	} else {
		echo "File is not an image.";
		$uploadOk = 0;
		exit();
	}

	// Check if file already exists
	if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
		exit();
	}

	// Check file size
	if ($_FILES["imagefile"]["size"] > 50000000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
		exit();
	}

	// Allow certain file formats
	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
		exit();
	}


	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
		exit();

		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["imagefile"]["tmp_name"], $target_file)) {
			echo "The file " . htmlspecialchars(basename($_FILES["imagefile"]["name"])) . " has been uploaded.";

			// Redirect the user back to the previous page.
			if (isset($_REQUEST["destination"])) {
				header("Location: {$_REQUEST["destination"]}");
			} else if (isset($_SERVER["HTTP_REFERER"])) {
				header("Location: {$_SERVER["HTTP_REFERER"]}");
			} else {
				/* some fallback, maybe redirect to index.php */
			}
		} else {
			echo "Sorry, there was an error uploading your file.";
			exit();
		}
	}



	//
	// UPDATE THE DATABASE WITH THE NEW IMAGE FILE
	//
	include 'config.php';
	$username = $_POST['username'];
	$time = time();
	$poi = $_GET['poi_id'];
	$city = $_GET['city_id'];

	$sql = "INSERT INTO Photo (filename, username, dateCreated, idPOI, idCity) VALUES (?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("ssiii", $target_imagename, $username, $time, $poi, $city);


	$stmt->execute();
}

?>

<h3>Upload photos of <?php echo $row['poiName'] ?></h3>
<form action="user_photos.php?poi_id=<?php echo $POIID ?>&city_id=<?php echo $row['idCity'] ?>" method="post" enctype="multipart/form-data">
	<p class="upload">
		<label for="username">Username:</label>
		<input type="text" name="username" placeholder="Username" maxlength=45>
	</p>
	<p class="upload">
		<label for="imagefile">Photo:</label>
		<input type="file" name="imagefile" id="imagefile" required>
	</p>

	<input type="submit" accept="image/*" name="submit" value="Upload">
</form>




<?php
include 'config.php';
$sql = "SELECT * FROM Photo WHERE idPOI=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $POIID);
$stmt->execute();

$result = $stmt->get_result();
while ($photo = $result->fetch_assoc()) {
	echo "<a href='uploads/user/" . $photo['filename'] . "' target='_blank'><img src='uploads/user/" . $photo['filename'] . "' width=300px /></a>";
	echo "<p><strong>Uploaded by:</strong> " . $photo['username'] . "</p>";
	echo "<p><strong>Time Uploaded:</strong> " . date('r', $photo['dateCreated']) . "</p>";
	echo "<br />";
	echo "<br />";
	echo "<br />";
}
?>