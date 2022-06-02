<?php
include 'config.php';

// Twitter
function GetTweets($setTag,$POIID, $cityID, $resultNumber){
    include 'config.php';
    require_once('TwitterAPIExchange.php');

    //Creating the url for the Twitter API call

    $url = "https://api.twitter.com/1.1/search/tweets.json";

    //Setting the api query with the poi name passed as $setTag and the $resultNumber for the number of tweets returned

    $getfield = "?q=".$setTag." filter:safe lang:en -filter:retweets -filter:replies&tweet_mode=extended&count=" .$resultNumber;

    // Building and executing the api query 

    $twitter = new TwitterAPIExchange($twitterSettings);

    $tweets = json_decode($twitter->setGetfield($getfield)
    ->buildOauth($url, "GET")
    ->performRequest(),$assoc = TRUE);


    // Creating variables to be used in foreach loop

    $IDarray = array();

    $i = 0;

    

    echo "<h2>Recent Tweets</h2>";


    // Loop to output every tweet found in the api query 

    foreach($tweets as $tweet){            
        while ($i<$resultNumber){
            if (sizeof($tweet)==0){

                // If no recent tweets are found then output this to the user and break out of the loop

                echo ("<br />No recent tweets found.<br />");
                $i=$resultNumber;
            }
            else{
                foreach ($tweet as $item){     
                    
                    // If the Tweet ID is already in the array it should continue to avoid printing duplicates
                    
                    if (in_array($item['id'], $IDarray))
                    {
                        
                    } 
                    else {
                        
                        // If the tweet has not already been output then assign the item values clearly defined variables 

                        $tweetID = $item['id'];
                        $tweetContent = $item['full_text'];
                        $username = $item['user']['screen_name'];


                        // Echo the author of the tweet
                        echo ("User: @" . $username . "<br />");

                        //Format and echo the time tweet was posted and then convert it to unix time in $dateCreated
                            
                        $unformattedTime = $item['created_at']; 
                        $formattedTime = str_replace("+0000 ","",$unformattedTime);
                        $dateCreated = strtotime($unformattedTime);

                        echo ("Posted at : ". $formattedTime . "<br />");

                        //Code to remove the link at the end of every tweet, if it exists

                        $pos = strrpos($tweetContent, " ");
                        
                        $lastPiece = substr($tweetContent, $pos, strlen($tweetContent));

                        if (str_contains($lastPiece, 'https://t.co')){
                            $tweetContent = substr($tweetContent, 0, $pos);
                            echo ($tweetContent . "<br />");
                        }
                        else{
                            echo ($tweetContent . "<br />");
                        }           
                        
                        // Add current tweet ID to the ID array

                        array_push($IDarray,$item['id']);

                        // Format the username and tweet so that it can be inserted to the DB with SQL

                        $formattedUsername = str_replace("'","''",$username);
                        $formattedTweet = str_replace("'","''",$tweetContent);


                        //  Create sql, prepare statemtebt abd then bind parameters

                        $sql = "INSERT IGNORE INTO tweets (id, tweet, username, dateCreated, idCity, idPOI) 
                        VALUES ( ?, ?, ?, ?, ?, ?)";

                        $stmt2 = $conn->prepare($sql);
                        
                        $stmt2->bind_param("ssssii", $tweetID, $formattedTweet, $formattedUsername, $dateCreated, $cityID, $POIID);

                        // Execute the query and if there is an issue then output error

                        if ($stmt2->execute()) {

                        } else {
                            echo ("<br />Error caching tweet.");
                        }

                        echo "<br /><br />";
            
                    }

                

                $i++;

            }

            }
      
        }
    }


    echo "<h2>Past Tweets</h2>";

    // Sql query to select 5 random past tweets for the current POI 

    $sql = "SELECT *
            FROM tweets
            WHERE idPOI = $POIID AND id not in( '" . implode( "', '" , $IDarray) . "' )
            ORDER BY RAND()
            LIMIT 5";

    $result = $conn->query($sql);

    // Output the 5 tweets from the SQL result

    if ($result->num_rows > 0) {

        while($row = $result->fetch_assoc()) {

            // covert unix time to readable date format
            $timestamp=$row["dateCreated"];
            $dateCreated = gmdate("D M d G:i:s Y", $timestamp);

            // Output tweet content
            echo "<br />User: @" . $row["username"]. "<br />Posted at: " .$dateCreated . "<br />" . $row["tweet"] . "<br />";
        }

        echo "<br /><br />";
    }
    else{
        echo "No historical tweets found.";
    }

}

// Flickr
function GetPhotos($tag, $resultNumber, $poi_id, $city_id){
    include 'config.php';

    // Make the search tag space-characters url valid.
    $tag = str_replace(" ","%20",$tag);    
    
    // Search for photos using the Flickr API.
    $url = 'http://api.flickr.com/services/rest/?method=flickr.photos.search';
    $url.= '&api_key='.$flickrAPI;
    $url.= '&tags='.$tag;
    $url.= '&text='.$tag;
    //$url.= '&woeid=2459115'; NYC
    //$url.= '&woeid=44418'; LDN
    //$url.= '&geo_context=2';
    $url.= '&safe_search=1';
    $url.= '&per_page='.$resultNumber;
    $url.= '&format=json';
    $url.= '&nojsoncallback=1';

    // Get the response from the API.
    $response = json_decode(file_get_contents($url));
    $photo_array = $response->photos->photo;
    
    // print ("<pre>");
    // print_r($response);
    // print ("</pre>");

    // Array used to keep track of which photos have been pulled from the API service whilst running the function.
    $flickrIDs = array();

    echo "<h2>Recent Flickr Photos</h2>";
    foreach($photo_array as $single_photo){
        // Save to variables for shorter variable names.
        $farm_id = $single_photo->farm;
        $server_id = $single_photo->server;
        $photo_id = $single_photo->id;
        $secret_id = $single_photo->secret;
        $size = 'm';
        
        $title = $single_photo->title;
        
        // Construct the url used to load the photo.
        $photo_url = 'http://farm'.$farm_id.'.staticflickr.com/'.$server_id.'/'.$photo_id.'_'.$secret_id.'_'.$size.'.'.'jpg';


        // Construct the uri and location for the photo to be saved to the filesystem and database with.
        $filename = $photo_id . ".jpg";
        $directory = "uploads/flickr/" . $filename;

        // Add current filename to the ID list.
        $flickrIDs[] = $photo_id;

        // If the photo has not already been cached to the filesystem, save it to the database and filesystem.
        if(!file_exists($directory)) {
            // Try download the photo from Flickr.
            $pic = @file_get_contents($photo_url);
            if($pic == false) {
                continue;
            }

            // Save the photo to the filesystem.
            file_put_contents($directory, $pic);

            
            // UPDATES THE DATABASE WITH THE NEW IMAGE FILE
            include 'config.php';
            $time = time();

            $sql = "INSERT IGNORE INTO Flickr (id, filename, title, dateCreated, idPOI, idCity) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiii", $photo_id, $filename, $title, $time, $poi_id, $city_id);

            $stmt->execute();

        }
        
        // Render the current photo into the HTML document.
        echo "<p><a href='" . $photo_url . "' target='_blank'><img title='" . $title . "' src='" . $photo_url . "' alt='" . $title . "' max-width=300px /></a></p>";
        echo "<p><strong>Title:</strong> " . $title . "</p>";
        echo "<p><strong>Time Fetched:</strong> " . date('r', time()) . "</p>";
        echo "<br />";
    
    }

    return $flickrIDs;
}

function GetHistoricalPhotos($poi_id, $city_id, $flickrPhotoIDs=array(), $page_number=1, $photos_per_page=5) {
    include 'config.php';

    // Construct a string of all the photo ID's which have already been loaded, so that they are not loaded again.
    $sqlNotIn = "";
    for($i = 0; $i < count($flickrPhotoIDs); $i++) {
        $sqlNotIn .= "'" . $flickrPhotoIDs[$i] . "'";
        if($i != count($flickrPhotoIDs) - 1) {
            // The last element
            $sqlNotIn .= ", ";
        }
    }
    $sqlNotIn .= "";

    // Construct the SQL statement to get the number of records for this specific POI.
    $sql = "SELECT count(id) as total FROM Flickr WHERE idPOI=? AND id NOT IN (" . $sqlNotIn . ")";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $poi_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $totalCount = $result->fetch_assoc();
    $totalCount = $totalCount['total'];


    $offset = $photos_per_page*$page_number-$photos_per_page;

    // Get all the POI photo records from the database.
    $sql = "SELECT * FROM Flickr WHERE idPOI=? AND id NOT IN (?) LIMIT ?,?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isii", $poi_id, $sqlNotIn, $offset, $photos_per_page);
    $stmt->execute();

    $result = $stmt->get_result();
    $number_of_results = $result->num_rows;


    // Sentence showing the current result statistics, like how many results are currently showing.
    echo '<p><strong>Showing past results ' . $photos_per_page*($page_number-1)+1 . '-' . $photos_per_page*$page_number . ' out of ' . $totalCount . ' results</strong></p>';
    echo "<br />";

    // Loop over every photo found in the database.
    while ($photo = $result->fetch_assoc()) {
        // If the photo is already in the array of photos just loaded from Flickr API, ignore it (to stop duplicating the same photo on the page).
        if(!in_array($photo['id'], $flickrPhotoIDs) && file_exists('uploads/flickr/' . $photo['filename'])) {
            // Echo out the photo entry.
            echo "<a href='uploads/flickr/" . $photo['filename'] . "' target='_blank'><img src='uploads/flickr/" . $photo['filename'] . "' alt='" . $photo['title'] . "' title='" . $photo['title'] . "' max-width=300px /></a>";
            echo "<p><strong>Title:</strong> " . $photo['title'] . "</p>";
            echo "<p><strong>Time Cached:</strong> " . date('r', $photo['dateCreated']) . "</p>";
            echo "<br/>";
            echo "<br/>";
            echo "<br/>";
        }
        
    }

    // Show the page control options. next pages and previous page controls.
    echo "<p>Page - <strong>$page_number</strong> out of <strong>" . ceil($totalCount/$photos_per_page) . "</strong></p>";
    if($page_number > 1) {
        echo "<a href='?poi=" . $_REQUEST["poi"] . "&flickrPage=" . $page_number - 1 . "'><p>Previous Page</p></a>";
    }
    if($page_number + 1 <= ceil($totalCount/$photos_per_page)) {
        echo "<a href='?poi=" . $_REQUEST["poi"] . "&flickrPage=" . $page_number + 1 . "'><p>Next Page</p></a>";
    }


}

// City Description
function cityDesc($cityID) {
    include 'config.php';
    
    // SQL query 
    $sql = "SELECT `description` FROM `City` WHERE `id` = $cityID";
    
    // Put SQL result into variable
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          echo " " . $row["description"]. "<br />";
        }
    }

    $conn->close();
}

// Map
function drawMap($cityID) {
    include 'config.php';

    // SQL Query statement
    $sql = "SELECT poi.id as poiID, 
            poi.name as poiName, 
            poi.description as poiDescription, 
            poi.latitude as poiLatitude, 
            poi.longitude as poiLongitude, 
            city.name as cityName, 
            city.latitude as cityLatitude, 
            city.longitude as cityLongitude
            FROM poi
            LEFT OUTER JOIN city ON poi.idCity = city.id
            WHERE city.id = $cityID";

    // Put SQL result into variable
    $result = $conn->query($sql);


    // Declare PHP arrays for each variable
    $nameArray = array();
    $descriptionArray = array();
    $latitudeArray = array();
    $longitudeArray = array();
    $poiIDArray = array();


    // Loop to add each variable from all the SQL query results

    // function truncate($string, $length, $dots = "...") {
    //     return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
    // }

    while($row = mysqli_fetch_array($result)) {
        $javascriptCityLat = $row['cityLatitude'];
        $javascriptCityLon = $row['cityLongitude'];
        $poiIDArray[] = $row["poiID"];
        $nameArray[] = $row["poiName"];
        $poiDescr = $row["poiDescription"];
        $poiDescr = preg_replace("/[^a-zA-Z0-9]+/", " ", $poiDescr);
        $descriptionArray[] = $poiDescr;
        $latitudeArray[] = $row["poiLatitude"];
        $longitudeArray[] = $row["poiLongitude"];
    }


    $javascriptNames = json_encode($nameArray);
    $javascriptDescriptions = json_encode($descriptionArray);
    $javascriptLats = json_encode($latitudeArray);
    $javascriptLons = json_encode($longitudeArray);
    $javascriptPOIIDs = json_encode($poiIDArray);

    $conn->close();

    // Create the map container
    //echo '<div id="map-' . $cityID . '"></div>';
    echo '<div id="map-' . $cityID . '"></div>';
    
    // This is the actual map
    include 'scripts.php';
}

// Weather
function currentWeather($cityID) {
    include 'config.php';

    // SQL query
    $sql = "SELECT latitude, longitude FROM City WHERE id = '$cityID'";
    $result = $conn->query($sql);

    while($row = mysqli_fetch_array($result)) {
        $lat = $row["latitude"];
        $lon = $row["longitude"];
    }
    $conn->close();
    
    // Pass in coords and load the weather from api
    $currentURL = "https://api.openweathermap.org/data/2.5/weather?lat=" . $lat . "&lon=" . $lon . "&appid=" . $weatherKey . "&mode=xml&units=metric";
    $xml = simplexml_load_file($currentURL);
    
    // Store relevant weather data as variables
    $lastUpdateData = $xml->lastupdate['value'];
    $pieces = explode("T", $lastUpdateData);
    $lastUpdateTime = $pieces[1];
    $lastUpdateDate = $pieces[0];

    $currentCondition = ucwords($xml->weather['value']);
    $currentConID = $xml->weather['icon'];
    $temperature = $xml->temperature['value'];
    $windSpeed = $xml->wind->speed['value'];
    $windUnit = $xml->wind->speed['unit'];
    $windDescription = $xml->wind->speed['name'];
    $windDirection = $xml->wind->direction['name'];

    $humidity = $xml->humidity['value'];

    $sunriseData = $xml->city->sun['rise'];
    $pieces = explode("T", $sunriseData);
    $sunrise = $pieces[1];

    $pieces = explode("T", $xml->city->sun['set']);
    $sunset = $pieces[1];

    // Output the weather data as html
    // echo "<div class='wthrElement'>";
    echo "<img class = 'pics' src='http://openweathermap.org/img/wn/$currentConID@4x.png'/>";
    echo "<h4>" . $temperature . "°C</h4>";
    echo "<h4>" . $currentCondition . "</h4>"; 
    echo "<p>Wind: <strong>" . $windSpeed . " " . $windUnit . "</strong> (" . $windDescription . ") from a <strong>" . $windDirection . " direction</strong></p>";
    echo "<p>Humidity: <strong>" . $humidity . "%</strong></p>";
    echo "<p>Sunrise: <strong>" . $sunrise . "</strong></p>";
    echo "<p>Sunset: <strong>" . $sunset . "</strong></p>"; 
    echo "<h5>Last updated at " . $lastUpdateTime . "</h5>";
    // echo "</div>";
}

function forecastWeather($cityID) {
    include 'config.php';

    // SQL query
    $sql = "SELECT latitude, longitude FROM City WHERE id = '$cityID'";
    $result = $conn->query($sql);

    while($row = mysqli_fetch_array($result)) {
        $lat = $row["latitude"];
        $lon = $row["longitude"];
    }
    $conn->close();

    // Pass in coords and load the weather from api
    $forecastURL = "https://api.openweathermap.org/data/2.5/forecast?lat=" . $lat . "&lon=" . $lon . "&appid=" . $weatherKey . "&mode=xml&units=metric";
    $xml = simplexml_load_file($forecastURL);

    // Store relevant weather data as variables
    $i = 0;
    foreach ($xml->forecast->time as $tags) {
        $startingTime = $xml->forecast->time[$i]['from'];

        $pieces = explode("T", $xml->sun['rise']);
        $sunrise = $pieces[1];

        $pieces = explode("T", $xml->sun['set']);
        $sunset = $pieces[1];

        if (str_contains($startingTime, '12:00:00')){
            
            $currentCondition = ucwords($xml->forecast->time[$i]->symbol['name']);
            $currentConID = $xml->forecast->time[$i]->symbol['var'];
            $temperature = $xml->forecast->time[$i]->temperature['value'];
            $windSpeed = $xml->forecast->time[$i]->windSpeed['mps'];
            $windUnit = $xml->forecast->time[$i]->windSpeed['unit'];
            $windDescription = $xml->forecast->time[$i]->windSpeed['name'];
            $windDirection = $xml->forecast->time[$i]->windDirection['name'];
            $humidity = $xml->forecast->time[$i]->humidity['value'];

            $pieces = explode("T", $startingTime);
            $date = $pieces[0];

            // Output weather data as html
            // echo "<div class='wthrElement'>";
            echo "<h3>" . $date . "</h3>";
            echo "<img class = 'pics' src='http://openweathermap.org/img/wn/$currentConID@4x.png'/>";
            echo "<h4>" . $temperature . "°C</h4>";
            echo "<h4>" . $currentCondition . "</h4>"; 
            echo "<p>Wind: <strong>" . $windSpeed . " " . $windUnit . "</strong> (" . $windDescription . ") from a <strong>" . $windDirection . " direction</strong></p>";
            echo "<p>Humidity: <strong>" . $humidity . "%</strong></p>";
            echo "<p>Sunrise: <strong>" . $sunrise . "</strong></p>";
            echo "<p>Sunset: <strong>" . $sunset . "</strong></p>"; 
            // echo "</div>";

            $i++;
        }

        else {
            $i++;
        }
    }
}
