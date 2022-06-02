<?php

// header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
// header("Cache-Control: post-check=0, pre-check=0", false);
// header("Pragma: no-cache");
?>


<!DOCTYPE html5>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap" rel="stylesheet"> -->

    <link href="app.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <title>Twin Cities</title>
    <?php
    require 'functions.php';
    require 'config.php';
    ?>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

</head>

<body>
    <div id='rss'>
        <a href="rss.php"><img src="rss.png" alt="RSS Icon"></a>
    </div>
    <h1>Twin Cities</h1>


    <menu>
        <section id="ldn">

            <h2>London</h2>

            <?php drawMap(1); ?>
            <h3>Weather in London right now</h3>
            <div id="ldnWthr">

                <?php currentWeather(1) ?>
                <h3>London - 5 Day Forecast</h3>
                <?php forecastWeather(1) ?>
            </div>

            <h3>Things to know about London</h3>
            <div id="ldnDescr">
                <?php cityDesc(1); ?>
            </div>
        </section>

        <section id="ny">
            <h2>New York</h2>
            <?php drawMap(2);?>

            <h3>Weather in New York right now</h3>
            <div id="nyWthr">
                <?php currentWeather(2) ?>
                <h3>New York - 5 Day Forecast</h3>
                <?php forecastWeather(2) ?>
            </div>


            <h3>Things to know about New York</h3>

            <div id="nyDescr">
                <?php cityDesc(2); ?>
            </div>
        </section>
    </menu>
</body>

</html>