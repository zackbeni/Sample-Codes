<?php
namespace TwinCities;
@date_default_timezone_set("GMT");

// Flickr Settings
$flickrAPI = '80871d4681f7b4e383cdd957de29c651';

// Twitter Settings
$twitterSettings = array(
    'oauth_access_token' => "1467567685147971586-Pf8opSu2YQDdmd1ekNkmj9XAILhTfY",
    'oauth_access_token_secret' => "ZEJQlqMIAZY4bUZRE3men1VAnRMonWxliSNZlSTM82SnY",
    'consumer_key' => "fOlGALRaoC6C8YLrLzqliiNSh",
    'consumer_secret' => "BBi57IpSWy9xgLRfZyJdBGXRsldMm4dAn6eHLOJaj8VzVLBA7W");

$weatherKey = "adda9c2e4369a0b53ccedf50ce41717e";

// DB Settings
$server = "localhost";
$username = "root";
$password = "";
$dbname = "Cities";

$conn = mysqli_connect($server, $username, $password, $dbname)

?>