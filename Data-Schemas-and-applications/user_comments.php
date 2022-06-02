<h3>Submit comments about <?php echo $row['poiName'] ?></h3>
<form method="post">
	<p>
	<label for="Username">Username:</label>
	<input type="text" name="Username" id="Username" placeholder="Username" maxlength=45>
</p>
<p>
	<label for="Comment">Comment:</label>
	<input type="text" name="Comment" id="Comment" placeholder="Comment" maxlength=280 required>
</p>
	<input type="submit" name="submitComment" value="Submit">
</form>

<?php

if (isset($_POST['submitComment'])) {

	$username = $_POST['Username'];
	$comment = $_POST['Comment'];
	$dateCreated = strtotime("now");

	$formattedUsername = str_replace("'", "''", $username);
	$formattedComment = str_replace("'", "''", $comment);

	$sql = "INSERT INTO comment (comment, username, dateCreated, idCity, idPOI) 
				VALUES ( '$formattedComment', '$formattedUsername', '$dateCreated', '$cityID', '$POIID')";

	if (mysqli_query($conn, $sql)) {
		echo "Submitted your comment:";
		echo ("User: " . $username);
		echo ("<br />Comment: " . $comment);
	} else {
		echo ("Error. Please try again.");
	}
}


$sql = "SELECT * FROM Comment WHERE idPOI=? ORDER BY dateCreated DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $POIID);
$stmt->execute();

$result = $stmt->get_result();

while ($comment = $result->fetch_assoc()) {
	echo "<p>Username: <strong>" . $comment['username'] . "</strong></p>";
	echo "<p>Comment: <strong>" . $comment['comment'] . "</strong></p>";
	echo "<p>Time Posted: <strong>" . date('Y-m-d H:i:s', $comment['dateCreated']) . "</strong></p>";

	echo "<br/>";
}
?>