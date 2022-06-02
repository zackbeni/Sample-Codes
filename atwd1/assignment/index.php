<?php

//turn off the error reporting to only display custom errors
error_reporting(0);
// error_reporting(E_ERROR | E_WARNING | E_PARSE);

//set default timezone to GMT;
date_default_timezone_set('GMT');
//get base currency passed
$fromCurrency = $_GET['from'];
//get target currency passed
$toCurrency = $_GET['to'];
//get amount value passed
$amnt = $_GET['amnt'];
//get format passed
$format = $_GET['format'];

//variable to keep track of error in service
$errorInService = false;
//variable to track whether there is an error
$NoError = '';

//load the dataStore with the initial 24 required currencies
$requiredCurrencies = simplexml_load_file('dataStore.xml');



//get data from xml file and store in the object
$xml1 = simplexml_load_file('currency_4217.xml');


//variable for the saved time from the dataStore
$fetchTime = $requiredCurrencies->xpath('//' . $toCurrency . '[@baseCurrency ="' . $fromCurrency . '"]/at');


//function to display caught errors
function displayError()
{

    global $requiredCurrencies, $fromCurrency, $toCurrency, $NoError, $amnt, $errorInService, $format;
    //fetch the status of the currency from data\store
    $fetchStatus = $requiredCurrencies->xpath('//' . $toCurrency . '[@baseCurrency ="' . $fromCurrency . '"]/@isActive');

    $errors = array(
        '1000' => 'Required Parameter is missing',
        '1100' => 'Parameter not recognized',
        '1200' => 'Currency type not recognized',
        '1300' => 'Currency amount must be a decimal number',
        '1400' => 'Format must be xml or json',
        '1500' => 'Error in service'
    );
    //keep track of the errors
    $errorSequence = [];

    //error 1000 - Required Parameter is missing
    if (
        empty($fromCurrency) or
        empty($toCurrency) or
        empty($amnt)
    ) {
        $errorID = 0;
        array_push($errorSequence, $errorID);
    }

    //error 1100 - Parameter not recognized
    if (
        !(array_key_exists('from', $_GET)) or
        !(array_key_exists('to', $_GET)) or
        !(array_key_exists('amnt', $_GET))
    ) {
        $errorID = 1;
        array_push($errorSequence, $errorID);
    }

    //error 1200 - Currency type not recognized'           
    if (
        //check if base and target currencieis are in the dataStore
        $requiredCurrencies->$fromCurrency->getName() and
        $requiredCurrencies->$toCurrency->getName()
    ) {
        //when they are stored
        $currNotStored = false;
    } else {
        //when they are not stored
        $currNotStored = true;
    }

    if (
        //when the currency was deleted (made unavailable)
        $fetchStatus[0][0] == 'no' or
        $currNotStored

    ) {
        $errorID = 2;
        array_push($errorSequence, $errorID);
    }

    //error 1300 - Currency amount must be a decimal number
    if (/*(float)$amnt == 0*/!(is_numeric($amnt))) {
        $errorID = 3;
        array_push($errorSequence, $errorID);
    }

    // error 1400 - Format must be xml or json
    if( (array_key_exists('format', $_GET))  and !(empty($format)) and ($format != 'xml' and $format != 'json') )

    {
        $errorID = 4;
        array_push($errorSequence, $errorID);
    }

    //error 1500 - Error in service
    if (
        $errorInService

    ) {
        $errorID = 5;
        array_push($errorSequence, $errorID);
    }
    // array containing the error keys
    $errorArrayKeys = array_keys($errors);

    //the key of the first error caugth
    $firstErrorCaughtKey = $errorArrayKeys[$errorSequence[0]];

    //create DOM document
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;
    $rootNode = $dom->createElement('conv');
    $dom->appendChild($rootNode);
    $errorNode = $dom->createElement('error');
    $rootNode->appendChild($errorNode);

    $codeNode = $dom->createElement('code', $firstErrorCaughtKey);
    $errorNode->appendChild($codeNode);

    $msgNode = $dom->createElement('msg', $errors[$firstErrorCaughtKey]);
    $errorNode->appendChild($msgNode);

    //display the error in xml
    if (count($errorSequence) > 0) {
        $NoError = false;
        header('Content-Type: application/xml');
        echo $dom->saveXML();

        return $NoError;
    //when no errors found
    } else {
        $NoError = true;
        return $NoError;
    }
}

//function to get rate and timestamp from api call
function apiCall()
{
    global $toCurrency, $fromCurrency, $errorInService;
    try {
        //api url 
        $api_url = 'https://freecurrencyapi.net/api/v2/latest?apikey=8dc4c730-6324-11ec-9fe0-47bbc5a9b277&base_currency=' . $fromCurrency;
        //save the response
        $response = file_get_contents($api_url);

        if (!($response)) {
            throw new Exception();
        }
        //decode the returned json data
        $json = json_decode($response);
        //get the rate from the api response data
        $rate = $json->data->$toCurrency;
        //get the timestamp from the api response data
        $timestamp = $json->query->timestamp;

        //return an array containing the rate and timestamp
        return array('rate' => $rate, 'timestamp' => $timestamp);
        //api call failed
    } catch (Exception $e) {
        //there is an error in service
        $errorInService = true;
        return false;
    }
}

//function to get locations where a currency is used
function getLocation($code)
{
    global $xml1;
    $loc = $xml1->xpath('CcyTbl/CcyNtry[Ccy="' . $code . '"]/CtryNm');
    $saveLoc = '';
    foreach ($loc as $x) {
        $saveLoc .= $x  . ', ';
    }
    return $saveLoc;
}

//function to convert the amount passed
function convert($rate)
{
    global $amnt;
    return $amnt * $rate;
}


//function to return xml response 
function xmlResponse($rate, $timestamp)
{
    global $xml1, $fromCurrency, $toCurrency, $amnt;

    //get the currency name of the base currency
    $fromCurrencyName = $xml1->xpath('CcyTbl/CcyNtry[Ccy="' . $fromCurrency . '"][position() < 2]/CcyNm');
    //get the currency of the target currency
    $toCurrencyName = $xml1->xpath('CcyTbl/CcyNtry[Ccy="' . $toCurrency . '"][position() < 2]/CcyNm');

    //set default timezone to GMT;
    date_default_timezone_set('GMT');
    //create a dom object
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;

    //create the root node
    $rootNode = $dom->createElement('conv');
    $dom->appendChild($rootNode);
    //create the at node
    $atNode = $dom->createElement('at', date("d M Y H:i", (int)$timestamp));
    $rootNode->appendChild($atNode);
    //create the rate node
    $rateNode = $dom->createElement('rate', $rate);
    $rootNode->appendChild($rateNode);

            //Base currency nodes creation
    $fromNode = $dom->createElement('from');
    $fromCodeNode = $dom->createElement('code', $fromCurrency);
    $fromNode->appendChild($fromCodeNode);

    $fromCurrNode = $dom->createElement('currencyName', $fromCurrencyName[0]);
    $fromNode->appendChild($fromCurrNode);

    $fromLocNode = $dom->createElement('loc', getLocation($fromCurrency));
    $fromNode->appendChild($fromLocNode);

    $fromAmntNode = $dom->createElement('amnt', $amnt);
    $fromNode->appendChild($fromAmntNode);

    //append the base Currency node to the root node
    $rootNode->appendChild($fromNode);

            //Target currency nodes creation
    $toNode = $dom->createElement('to');
    $toCodeNode = $dom->createElement('code', $toCurrency);
    $toNode->appendChild($toCodeNode);

    $toCurrNode = $dom->createElement('currencyName', $toCurrencyName[0]);
    $toNode->appendChild($toCurrNode);

    $toLocNode = $dom->createElement('loc', getLocation($toCurrency));
    $toNode->appendChild($toLocNode);

    $toAmntNode = $dom->createElement('amnt', convert($rate));
    $toNode->appendChild($toAmntNode);

    //append the target Currency node to the root node
    $rootNode->appendChild($toNode);

    //echo the formatted xml

    header('Content-Type: application/xml');
    echo $dom->saveXML();
}

//function to return json response
function jsonResponse($rate, $timestamp)
{
    global $xml1, $fromCurrency, $toCurrency, $amnt;

    //get the currency name of the base currency
    $fromCurrencyName = $xml1->xpath('CcyTbl/CcyNtry[Ccy="' . $fromCurrency . '"][position() < 2]/CcyNm');
    //get the currency of the target currency
    $toCurrencyName = $xml1->xpath('CcyTbl/CcyNtry[Ccy="' . $toCurrency . '"][position() < 2]/CcyNm');

    //create a json data array
    $jsonArray = array(
        'conv' => array(
            'at' => date("d M Y H:i", (int)$timestamp),
            'rate' => $rate,
            'from' => array(
                'code' => $fromCurrency,
                'currencyName' => strval($fromCurrencyName[0]),
                'loc' => getLocation($fromCurrency),
                'amnt' => $amnt
            ),
            'to' => array(
                'code' => $toCurrency,
                'currencyName' => strval($toCurrencyName[0]),
                'loc' => getLocation($toCurrency),
                'amnt' => convert($rate)
            )
        )
    );

    //echo the formatted json
    echo '<pre>' . json_encode($jsonArray, JSON_PRETTY_PRINT) . '</pre>';
}

//function to compare two times
function compareTimes($savedTimestamp)
{
    //current time
    $now = new DateTime();

    //previous time
    $past = new DateTime();
    $past->setTimestamp($savedTimestamp);

    //interval between the two times
    $interval = date_diff($now, $past);

    $hoursInSec = $interval->format("%H") * 3600;
    $minutesInSec = $interval->format("%i") * 60;
    $seconds = $interval->format("%s");

    //time in seconds
    $diffInSec = $hoursInSec + $minutesInSec + $seconds;

    //wjhen rate was stored more than 2 hours 
    if ($diffInSec >= 7200) {
        return true;

        //wjhen rate was stored lessthan 2 hours 
    } else {
        return false;
    }
}

//function to execute the Conversion Microservice
function runService()
{
    global $xml1, $requiredCurrencies, $fromCurrency, $toCurrency, $amnt;

    //variable for the saved time from the dataStore
    $fetchTime = $requiredCurrencies->xpath('//' . $toCurrency . '[@baseCurrency ="' . $fromCurrency . '"]/at');

    //variable for the saved timestamp from the dataStore
    $fetchTimestamp = $requiredCurrencies->xpath('//' . $toCurrency . '[@baseCurrency ="' . $fromCurrency . '"]/timestamp');

    //variable for the saved rate from the dataStore
    $fetchRate = $requiredCurrencies->xpath('//' . $toCurrency . '[@baseCurrency ="' . $fromCurrency . '"]/rate');

    //variable for the saved old rate from the dataStore
    $fetchOldRate = $requiredCurrencies->xpath('//' . $toCurrency . '[@baseCurrency ="' . $fromCurrency . '"]/oldRate');

    //get the currency name of the target currency
    $toCurrencyName = $xml1->xpath('CcyTbl/CcyNtry[Ccy="' . $toCurrency . '"][position() < 2]/CcyNm');

    //if two hours have passed since the rate was fetched
    if (compareTimes((int)$fetchTimestamp[0][0]) and $fromCurrency == 'GBP') {

        //invoke the apicall() and fetch new rate and timestamp
        $apiData = apiCall();

        //Error in service if api call fails
        if (!($apiData)) {
            displayError();
            return;
        }
        //get the rate
        $rate = $apiData['rate'];
        //get the timestamp
        $timestamp = $apiData['timestamp'];

        //invoke convert() to convert the amount
        convert($rate);

        //display the XML response by default or when requested
        if (
            $_GET['format'] == 'xml' or
            //when parameter has no value default to xml
            $_GET['format'] == '' or
            //when format parameter is missing in the URL default to xml
            !(array_key_exists('format', $_GET))

        ) {
            xmlResponse($rate, $timestamp);
        }
        //display JSON response when requested
        if ($_GET['format'] == 'json') {
            jsonResponse($rate, $timestamp);
        }

        //update the decoded timestamp
        $fetchTime[0][0] = date('d M Y H:i', $timestamp);
        //update the raw timestamp
        $fetchTimestamp[0][0] = $timestamp;
        //update the oldrate 
        $fetchOldRate[0][0] = $fetchRate[0];
        //save the new rate
        $fetchRate[0][0] = $rate;

        //add the changes to the dataStore file
        file_put_contents('dataStore.xml', $requiredCurrencies->saveXML());

        //when the rate was fetched less than 2 hours ago
    } elseif(!(compareTimes((int)$fetchTimestamp[0][0])) and $fromCurrency == 'GBP') {
        //variable for the saved timestamp from the dataStore
        $fetchTimestamp = $requiredCurrencies->xpath('//' . $toCurrency . '[@baseCurrency ="' . $fromCurrency . '"]/timestamp');

        //variable for the saved rate from the dataStore
        $fetchRate = $requiredCurrencies->xpath('//' . $toCurrency . '[@baseCurrency ="' . $fromCurrency . '"]/rate');


        //invoke convert() to convert the amount using the rate from dataStore
        convert($fetchRate[0][0]);

        //display XML response using the stored rate and timestamp
        if (
            $_GET['format'] == 'xml' or
            //when parameter has no value default to xml
            $_GET['format'] == '' or
            //when parameter is missing in the URL default to xml
            !(array_key_exists('format', $_GET))

        ) {
            xmlResponse($fetchRate[0][0], $fetchTimestamp[0][0]);
        }
        //display JSON response using the stored rate and timestamp
        if ($_GET['format'] == 'json') {
            jsonResponse($fetchRate[0][0], $fetchTimestamp[0][0]);
        }
        
        //when the base/reference currency is GBP
    }else{
        //invoke the apicall() and fetch new rate and timestamp
        $apiData = apiCall();

        //Error in service if api call fails
        if (!($apiData)) {
            displayError();
            return;
        }
        //get the rate
        $rate = $apiData['rate'];
        //get the timestamp
        $timestamp = $apiData['timestamp'];

        //invoke convert() to convert the amount
        convert($rate);

        //display the XML response by default or when requested
        if (
            $_GET['format'] == 'xml' or
            //when parameter has no value default to xml
            $_GET['format'] == '' or
            //when format parameter is missing in the URL default to xml
            !(array_key_exists('format', $_GET))

        ) {
            xmlResponse($rate, $timestamp);
        }
        //display JSON response when requested
        if ($_GET['format'] == 'json') {
            jsonResponse($rate, $timestamp);
        }

    }
}

//invoke displayError() to ouytput the errors if any
displayError();

//if there are no errors invoke runService() 
if ($NoError) {
    runService();
}
