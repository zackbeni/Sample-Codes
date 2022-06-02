<?php
//include the config file
include 'config.php';

header("Content-Type: text/xml");
// header('Content-Type: application/rss+xml; charset=utf-8');
//xml declaration
echo '<?xml version="1.0" encoding="UTF-8"?>';
//rss declaration with custom namespace
echo '<rss version="2.0" xmlns:content="http://G-02-DSA" xmlns:atom="http://www.w3.org/2005/Atom">';
echo "<channel>";

echo '<title>Cities RSS</title>';
echo "<link>http://localhost" . $_SERVER["REQUEST_URI"] . "</link>";
echo '<atom:link href="http://localhost' . $_SERVER["REQUEST_URI"] . '" rel="self" type="application/rss+xml" />';
echo "<description>Updates on Cities</description>";

//query for city table
$citySQL = 'SELECT * FROM city ORDER BY id DESC';
//city query results
$cityResult = $conn->query($citySQL);

//all data item opening tag 

//Display city and poi data
while ($cityRow = $cityResult->fetch_assoc()) {

    //city item opening tag
    for ($i = 0; $i < 1; $i++) {
        echo '<item>'.
             '<title>' . $cityRow['name'] . '</title>'.
             '<link>' . $cityRow['wikiLink'] . '</link>'.
             '<description>' . $cityRow['description'] . '</description>'.
             "<content:country>" . $cityRow['country'] . "</content:country>".
             "<content:state>" . $cityRow['state'] . "</content:state>".
             "<content:area>" . $cityRow['area'] . "</content:area>".
             "<content:population>" . $cityRow['population'] . "</content:population>".
             "<content:latitude>" . $cityRow['latitude'] . "</content:latitude>".
             "<content:longitude>" . $cityRow['longitude'] . "</content:longitude>".
             "<content:currency>" . $cityRow['currency'] . "</content:currency>".
             "<guid isPermaLink=\"false\">City-" . $cityRow['id'] . "</guid></item";

        //display poi data
        $poiSQL = 'SELECT * FROM poi WHERE idCity=? ORDER BY id DESC';
        $poistmt = $conn->prepare($poiSQL);
        $poistmt->bind_param("i", $cityRow['id']);
        $poistmt->execute();
    
        $poiResult = $poistmt->get_result();
        // echo '<item>';
        while ($poiRow = $poiResult->fetch_assoc()) {
  
            //poi item closing tag
            echo '<item>';
            for ($j = 0; $j < 1; $j++) {
                echo '<title>' . $poiRow['name'] . '</title>'.
                     '<link>' . $poiRow['wikiLink'] . '</link>';

                $poiDescr = $poiRow['description'];
                $poiDescr = preg_replace("/[^a-zA-Z0-9]+/", " ", $poiDescr);
                echo "<description>" . $poiDescr . "</description>";

                // Get all the categories.
                $catSQL = "SELECT POI.id, Category.name as categoryName
                        FROM POI
                        LEFT JOIN POI_Category ON poi.id = POI_Category.idPOI
                        LEFT JOIN Category ON Category.id = POI_Category.idCategory
                        WHERE POI.id = ?";
                $catstmt = $conn->prepare($catSQL);
                $catstmt->bind_param("i", $poiRow['id']);
                $catstmt->execute();
            
                $catResults = $catstmt->get_result();

                while ($category = mysqli_fetch_array($catResults)) {
                    echo "<category>" . $category['categoryName'] . "</category>";
                }

                // Lat and Lon and Opening times. Made using custom namespace.
                echo "<content:latitude>" . $poiRow['latitude'] . "</content:latitude>";
                echo "<content:longitude>" . $poiRow['longitude'] . "</content:longitude>";
                echo "<content:openings>" . $poiRow['openings'] . "</content:openings>";
                echo "<guid isPermaLink=\"false\">POI-" . $poiRow['id'] . "</guid>";

            }
            //poi item closing tag
            echo '</item>';
        }
    }
}
echo '</channel>';
echo '</rss>';

//close database connection
$conn->close();
?>
