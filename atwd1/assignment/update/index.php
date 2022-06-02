<?php
//prevent error reporting
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//set default timezone to GMT;
date_default_timezone_set('GMT');

// ../requiredCurrencies.xml
$xml1 = simplexml_load_file('../currency_4217.xml') or die('Error: cannot load file');
$requiredCurrencies = simplexml_load_file('../dataStore.xml');

//initialise variable error in service to false as no error detected yet
$errorInService = false;

//get the passed parameter values
$action = $_GET['action'];
$cur = $_GET['cur'];


//fetch the status of the currency from datastore
$fetchStatus = $requiredCurrencies->xpath('//' . $cur . '/@isActive');

//function to get rate and timestamp from api call
function apiCall()
{
    //access the global varibales $cur and $errorInService
    global $cur, $errorInService;

    try {
        //api url 
        $api_url = 'https://freecurrencyapi.net/api/v2/latest?apikey=8dc4c730-6324-11ec-9fe0-47bbc5a9b277&base_currency=GBP';
        //save the response
        $response = file_get_contents($api_url);

        if (!($response)) { //throw an exception when api call is unsuccessful 
            throw new Exception();
        }
        //decode the returned json data
        $json = json_decode($response);
        //get the rate from the api response data
        $rate = $json->data->$cur;
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

//function to return an xml resonse 
function xmlResponse()
{
    // header('Content-Type: application/xml');
    global $xml1, $cur, $requiredCurrencies, $action;
    $currName = $xml1->xpath('CcyTbl/CcyNtry[Ccy="' . $cur . '"][position() < 2]/CcyNm');

    //variable for the saved time from the dataStore
    $fetchTime = $requiredCurrencies->xpath('//' . $cur . '/at');

    //variable for the saved timestamp from the dataStore
    $fetchTimestamp = $requiredCurrencies->xpath('//' . $cur . '/timestamp');

    //variable for the saved rate from the dataStore
    $fetchRate = $requiredCurrencies->xpath('//' . $cur . '/rate');

    //variable for the saved old rate from the dataStore
    $fetchOldRate = $requiredCurrencies->xpath('//' . $cur . '/oldRate');

    //set default timezone to GMT;
    date_default_timezone_set('GMT');

    //create dom object
    $dom = new DOMDocument('1.0', 'UTF-8');
    // $doc->preserveWhiteSpace = false;
    $dom->formatOutput = true;

    $rootNode = $dom->createElement('action');
    $dom->appendChild($rootNode);
    $rootNode->setAttribute('type', $action);
    $atNode = $dom->createElement('at', date("d M Y H:i", (int)$fetchTimestamp[0][0]));
    $rootNode->appendChild($atNode);

    if ($action == 'put' or $action == 'post') {
        $rateNode = $dom->createElement('rate', $fetchRate[0][0]);
        $rootNode->appendChild($rateNode);

        if ($action == 'put') {
            $oldRateNode = $dom->createElement('oldRate', $fetchOldRate[0][0]);
            $rootNode->appendChild($oldRateNode);
        }

        $currNode = $dom->createElement('curr');
        $codeNode = $dom->createElement('code', $cur);
        $currNode->appendChild($codeNode);

        $currNameNode = $dom->createElement('currencyName', $currName[0]);
        $currNode->appendChild($currNameNode);

        $locNode = $dom->createElement('loc', getLocation($cur));
        $currNode->appendChild($locNode);

        //append the currNode
        $rootNode->appendChild($currNode);
    }
    //if the action is Delete
    if ($action == 'del') {
        $codeNode = $dom->createElement('code', $cur);
        $rootNode->appendChild($codeNode);
    }


    //display the xml response
    header('Content-Type: application/xml');
    echo $dom->saveXML();
}

$NoError = '';
//function to display caught error
function displayError()
{
    global $xml1, $requiredCurrencies, $action, $cur, $NoError, $amnt, $errorInService;
    //set default timezone to GMT;
    date_default_timezone_set('GMT');

    //fetch the status of the currency from data\store
    $fetchStatus = $requiredCurrencies->xpath('//' . $cur . '/@isActive');

    //variable for the saved rate from the dataStore
    $fetchRate = $requiredCurrencies->xpath('//' . $cur . '/rate');

    //array for the actions
    $actions = array('put', 'post', 'del');

    //array containing all the errors and their codes
    $errors = array(
        '2000' => 'Action not recognized or is missing',
        '2100' => 'Currency code in wrong format or is missing',
        '2200' => 'Currency code not found for update',
        '2300' => 'No rate listed for this currency',
        '2400' => 'Cannot update base currency',
        '2500' => 'Error in service'
    );
    //keep track of the errors
    $errorSequence = [];

    //error 2000 - Action not recognized or is missing
    if (
        empty($action) or
        !(in_array($_GET['action'], $actions)) or
        !(array_key_exists('action', $_GET))
    ) {
        $errorID = 0;
        array_push($errorSequence, $errorID);
    }

    //error 2100 - Currency code in wrong format or is missing

    //check if currency passed is a valid one in the ISO file
    $checkCurrency =  $xml1->xpath('CcyTbl/CcyNtry[Ccy="' . $cur . '"]');

    if (
        !$checkCurrency and
        array_key_exists('cur', $_GET)
    ) {
        $errorID = 1;
        array_push($errorSequence, $errorID);
    }

    //error 2200 - Currency code not found for update   

    //check if currency is in the dataStore and available to the service
    if (
        (array_key_exists('cur', $_GET) and ($action == 'put' or $action == 'del') and !($requiredCurrencies->$cur->getName())) or
        (array_key_exists('cur', $_GET) and ($action == 'put' or $action == 'del') and $fetchStatus[0][0] == 'no')

    ) {
        //when currency is not in the dataStore xml file
        $currNotStored = true;
    }
    if ($currNotStored) {
        $errorID = 2;
        array_push($errorSequence, $errorID);
    }
    //variable to check if rate is available from api data
    $checkRate = apiCall();

    // error 2300 - No rate listed for this currency
    if (

        array_key_exists('cur', $_GET) and ($action == 'post') and
        $checkRate['rate'] == NULL
    ) {
        $errorID = 3;
        array_push($errorSequence, $errorID);
    }
    //error 2400 - Cannot update base currency
    if (($requiredCurrencies->$cur->getName() == 'GBP') and
        ($action == 'put' or $action == 'del')
    ) {
        $errorID = 4;
        array_push($errorSequence, $errorID);
    }

    //error 2500 - Error in service

    if (
        !(array_key_exists('cur', $_GET)) or
        ($fetchStatus[0][0] == 'yes' and $action == 'post') or
        $errorInService
    ) {
        $errorID = 5;
        array_push($errorSequence, $errorID);
    }
    $errorArrayKeys = array_keys($errors);
    $firstErrorCaughtKey = $errorArrayKeys[$errorSequence[0]];

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;
    $rootNode = $dom->createElement('action');
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

        //display the xml response
        header('Content-Type: application/xml');
        echo $dom->saveXML();

        //return the error status
        return $NoError;
    } else {
        $NoError = true;

        //return the error status
        return $NoError;
    }
}

//function for make a put request
function put()
{
    global $cur, $action, $requiredCurrencies, $errorInService;
    //invoke the apicall() and fetch new rate and timestamp
    $apiData = apiCall();

    //Error in service if api call fails
    if (!($apiData)) {
        displayError();
        //terminate function execution
        return;
    }
    //get the rate
    $rate = $apiData['rate'];
    //get the timestamp
    $timestamp = $apiData['timestamp'];

    //variable for the saved time from the dataStore
    $fetchTime = $requiredCurrencies->xpath('//' . $cur . '/at');

    //variable for the saved timestamp from the dataStore
    $fetchTimestamp = $requiredCurrencies->xpath('//' . $cur . '/timestamp');

    //variable for the saved rate from the dataStore
    $fetchRate = $requiredCurrencies->xpath('//' . $cur . '/rate');

    //variable for the saved old rate from the dataStore
    $fetchOldRate = $requiredCurrencies->xpath('//' . $cur . '/oldRate');

    //update the decoded timestamp
    $fetchTime[0][0] = date('d M Y H:i', $timestamp);
    //update the raw timestamp
    $fetchTimestamp[0][0] = $timestamp;
    //update the oldrate 
    $fetchOldRate[0][0] = $fetchRate[0];
    //save the new rate
    $fetchRate[0][0] = $rate;

    //add the changes to the dataStore file
    file_put_contents('../dataStore.xml', $requiredCurrencies->saveXML());

    //invoke the xmlResponse() function 
    xmlResponse();
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

//function to do POST
function post()
{
    global $xml1, $cur, $action, $dataStore, $requiredCurrencies;

    //fetch the status of the currency from dataStore
    $fetchStatus = $requiredCurrencies->xpath('//' . $cur . '/@isActive');

    //when currency to post is in datastore but was deleted before
    if ($fetchStatus) {
        //update the active status of the currency to make it available to service
        $fetchStatus[0]['isActive'] = 'yes';
        //do a put on the currency by updating the rates
        put();
        //terminate function execution
        return;
    }

    try {
        //invoke the apicall() and fetch new rate and timestamp
        $apiData = apiCall();

        if (!($apiData)) {
            throw new Exception();
        }
        //get the rate
        $rate = $apiData['rate'];
        //get the timestamp
        $timestamp = $apiData['timestamp'];
        //display error in service on api call failure
    } catch (Exception $e) {
        echo 'I have run';
        $errorInService = true;
        displayError();
        return;
    }

    if (!$fetchStatus) {
        //get the currency name from the ISO Currencies file
        $currencyName = $xml1->xpath('CcyTbl/CcyNtry[Ccy="' . $cur . '"][position() < 2]/CcyNm');

        //add a new currency in xml format in the dataStore file
        $curr = $requiredCurrencies->addChild($cur);

        //attribute to specify if a currency is available or not
        $curr->addAttribute('isActive', 'yes');
        //attribute to specify what basecurrency the target currency rate is relative to
        $curr->addAttribute('baseCurrency', 'GBP');

        //currency name node
        $currName = $curr->addChild('currencyName', $currencyName[0][0]);

        //time the rate was fetched
        $at = $curr->addchild('at', date("d M Y H:i", $timestamp));
        //raw timestamp
        $rawTimestamp = $curr->addchild('timestamp', $timestamp);
        //new rate
        $toRate = $curr->addChild('rate', $rate);

        //set the toOldRate to null initially as there is no old rate
        $oldRate = $curr->addchild('oldRate', 'NULL');

        //add the locations where currency is used
        $loc = $curr->addchild('loc', getLocation($cur));
    }

    //add the changes to the dataStore file
    file_put_contents('../dataStore.xml', $requiredCurrencies->saveXML());

    //invoke the xmlResponse()
    xmlResponse();
}
function delete()
{
    global $cur, $requiredCurrencies;
    //fetch the status of the currency from data\store
    $fetchStatus = $requiredCurrencies->xpath('//' . $cur . '/@isActive');

    //update the active status of the currency
    $fetchStatus[0]['isActive'] = 'no';

    //add the changes to the dataStore file
    file_put_contents('../dataStore.xml', $requiredCurrencies->saveXML());

    //invoke the xmlResponse()
    xmlResponse();
}

// invoke the displayError() to check for errors and display them if any
displayError();

//invoke the put() if there are no errors and action 'put' was passed
if ($action == 'put' and $NoError) {
    put();
}
//invoke the post() if there are no errors and action 'post' was passed
if ($action == 'post' and $NoError) {
    post();
}
//invoke the delete() if there are no errors and action 'del' was passed
if ($action == 'del' and $NoError) {
    delete();
}
