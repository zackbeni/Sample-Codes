<!DOCTYPE <!DOCTYPE html5>
<html lang="en">

<head>
	<title>POI Page</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="poi.css" rel="stylesheet">
	<!-- include google fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap" rel="stylesheet">
	<?php include 'config.php'; ?>
	<?php include 'functions.php'; ?>
</head>

<body>
	
		<?php
		// Get the POI name from the URL of the page
	
		$uri = $_SERVER['REQUEST_URI'];

		$POIID = $_REQUEST['poi'];

		// Query DB using POI name
		// OLD SQL: LEFT JOIN category ON poi.category = category.id 
		$sql = "SELECT poi.id, poi.idCity, 
				poi.name as poiName, 
				poi.description, poi.photo, poi.wikiLink, poi.openings,
				city.name as cityName, 
				city.country, city.state
				from poi
				LEFT OUTER JOIN city ON poi.idCity = city.id
				WHERE poi.id = '$POIID'";

		$result = $conn->query($sql);

		// Output POI info
		while ($row = mysqli_fetch_array($result)) {
			echo '
				<a href="index.php"><input id = "homeBtn"  type="button" value="Home"></a>
				<div id = "rss"><a href="rss.php"><img src="rss.png" alt="RSS Icon"></a></div>';
			echo "<h1>" . $row["poiName"] . "</h1>";
			echo "<p id = 'description'>" . $row['description'] . "</p>";
			//echo '<img src="data:image/jpeg;base64,' . base64_encode($row['photo']) . '"/><br>';
			echo "<p class = 'details'>Opening times: " . $row['openings'] . "</p>";
			echo "<p class = 'details'><a href='" . $row["wikiLink"] . "'>" . $row["poiName"] . " Wiki page</a></p>";

			if ($row['country'] == "US") {
				echo "<p class = 'details'>Location: " . $row['cityName'] . ", " . $row['state'] . ", " . $row['country'] . "</p>";
			} else {
				echo "<p class = 'details'>Location: " . $row['cityName'] . ", " . $row['country'] . "</p>";
			}


			// Find all the categories.
			$sql = "SELECT POI.id, Category.name as categoryName
					FROM POI
					LEFT JOIN POI_Category ON poi.id = POI_Category.idPOI
					LEFT JOIN Category ON Category.id = POI_Category.idCategory
					WHERE POI.id = $POIID";
			$result = $conn->query($sql);

			$categories_str = "";
			while ($category = mysqli_fetch_array($result)) {
				if($categories_str == "") {
					$categories_str .= $category["categoryName"];
				} else {
					$categories_str .= ", " . $category["categoryName"];
				}
			}
			echo "<p class = 'details'>Category: " . $categories_str . "</p>";
			echo "</div>";

			$POIID = $row['id'];
			$cityID = $row['idCity'];

			echo '<menu id = "poi"><section id = "tf"> <div id = "tweets">';
			GetTweets($row["poiName"], $POIID, $cityID, 3);
			echo '</div>';

			echo '<div id = "flickr">';
			$flickrPhotoIDs = GetPhotos($row["poiName"], 3, $POIID, $cityID);
			echo "<h2>Past Flickr</h2>";
			GetHistoricalPhotos($POIID, $cityID, $flickrPhotoIDs, 8);
			echo "</div></section>";

			// Include comment box logic and html
			echo '<section id = "input"><div id = "comments"><h2>Got something to say?</h2>';
			include_once("user_comments.php");
			echo "</div>";

			// Include photos upload logic and html
			echo '<div id = "photos"><h2>Have better photos?</h2>';
			include_once("user_photos.php");
			echo "</div></section></menu";
		}
		$conn->close();
		?>
	</menu>
</body>
</html>