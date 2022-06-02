<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Test Requests Form</title>

    <!-- css -->
    <style>
        body,
        input,
        textarea,
        #hidden,
        #selectPlaceholder {
            background-color: black;
            color: white;
        }
        #h1{
            position: absolute;
            top: 10px;
            /* left: 45px; */

        }
        {
            position: absolute;
            top: 100px;

        }

        #actionLabel {
            position: absolute;
            top: 75px;
            left: 10px;
        }
        #put {
            position: absolute;
            top: 75px;
            left: 55px;
        }
        #putLabel {
            position: absolute;
            top: 75px;
            left:75px;
        }
        #post {
            position: absolute;
            top: 75px;
            left: 110px;
       
        }
       
        #postLabel {
            position: absolute;
            top: 75px;
            left: 130px;
            
        }
        #del {
            position: absolute;
            top: 75px;
            left: 175px;
        }
      
        #deleteLabel {
            position: absolute;
            top: 75px;
            left: 195px;
        }
         
         #selectLabel {
            position: absolute;
            top: 110px;
            left: 10px;
            width: fit-content;
            height: fit-content;

        }
        #cur {
            position: absolute;
            top: 110px;
            left: 45px;
            width: fit-content;
            height: fit-content;
        }

        select:focus+.hint {
            display: inline;
        }


        #btn {
            position: absolute;
            top: 140px;
            left: 10px;
            background-color:white;
            color: black;
        }

        #response {
            display: block;
            margin-bottom: 10px;
            width: auto;
            height: auto;
            position: absolute;
            top: 195px;
            left: 10px;
        }

        #responseLabel {
            position: absolute;
            top: 175px;
            left: 10px;
            width: fit-content;
            height: fit-content;
        }
        

        .hint {
            display: none;
            color: white;
            font-style: italic;
            position: absolute;
            top: 100px;
            right: 60px;
            text-align: left;
            font-size: smaller;

        }

        option {
            background-color: black;
        }

        .available {
            color: lightgreen;
        }

        .unavailable {
            color: red;
        }
    </style>


    <!-- Include axios library -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- include jquery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <!-- javascript -->
    <script>
        $(function() {

            //event listener on the form submit button
            $("#btn").click(function(e) {
                //prevent the default form submission
                e.preventDefault();

                //fetch the currency code passed in the form
                let cur = $('#cur').val();
                //fetch the action passed in the form
                let action = $('input[name=action]:checked', '#testAction').val();

                //variable to track currency is stored before changing colour
                let notStored = false;

                //get request for dataStore xml file
                axios.get('../dataStore.xml').then(res => {

                    //fetch the data
                    const response = res.data;

                    //create a dom parser
                    const parser = new DOMParser();
                    const currencies = parser.parseFromString(response, 'application/xml');
                    //get the target currency from datastore
                    const targetCurrency = currencies.getElementsByTagName(cur)[0];
                    //get the status of the target currency
                    const status = targetCurrency.getAttribute('isActive');

                    //handle errors for the datastore get request
                }).catch(error => {
                    notStored = true;
                })


                $.ajax({
                    //send a post request to the server file requests.php
                    type: "POST",
                    url: "requests.php",
                    //data to be sent via the post request
                    data: {
                        cur: cur,
                        action: action

                    },
                    //prevent the caching of data for jquery
                    cache: false,
                    success: async function(data) {

                        //disable the cache for the axios 
                        axios.defaults.headers = {
                            'Cache-Control': 'no-cache',
                            'Pragma': 'no-cache',
                            'Expires': '0',
                        };

                        try {
                            //get the xml response from the response file 
                            const res = await axios.get('response.xml');
                            //fetch the data
                            const response = res.data;

                            //create a dom parser
                            const parser = new DOMParser();
                            const xmlResponse = parser.parseFromString(response, 'application/xml');
                            const errorCode = xmlResponse.getElementsByTagName('code')[0];
                            console.log(errorCode.innerHTML);

                            //display it in the textarea element
                            $('#response').html(res.data);

                            //change the colour of the new added currency to light green as it's active

                            //when currency is not stored and rate is availale from api 
                            if ((action == 'post' & notStored & errorCode.innerHTML != '2300') ||
                                //when the currency is stored but made unavailable by delete    
                                (action == 'post' & !notStored)) {

                                for (const o of document.querySelectorAll("option")) {
                                    if (o.textContent.includes(cur)) {
                                        o.style.color = "lightgreen";
                                    }
                                }
                            }
                            //change the colour of the deleted currency to red as it's unavailable
                            if (action == 'del') {
                                for (const o of document.querySelectorAll("option")) {
                                    if (o.textContent.includes(cur)) {
                                        o.style.color = "red";
                                    }
                                }
                            }
                        } catch (err) {
                            // catch any errrors
                            console.error(err);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                    }
                });


            });

        });
    </script>



</head>

<body>
    <h1 id = 'h1'>Form Interface for POST, PUT and DELETE</h1>

    <?php

    //access the datastore
    $requiredCurrencies = simplexml_load_file('../dataStore.xml') or die('Error: cannot create object');

    $check = [];
    //function to select currencies from the ISO currenct file
    function selectCurrencies()
    {
        global $requiredCurrencies, $check;
        //access the ISO currency file
        $xml1 = simplexml_load_file('../currency_4217.xml') or die('Error: cannot load file');
        //array variable to track currencies that are already on the list
        $track = [];
        foreach ($xml1->CcyTbl->Children() as $a) {

            //code to remove repetition of currency codes and remove no universal currency
            if (
                in_array(strval($a->Ccy[0][0]), array_keys($track)) or
                $a->CcyNm[0][0] == 'No universal currency'
            ) {
                continue;
            }
            //add the currency for tracking
            $track[strval($a->Ccy[0][0])] = strval($a->CcyNm[0][0]);
            //sort the array values alphabetically
            ksort($track);
            if (strval($a->Ccy[0][0] == 'ZWL')) {
                //add the currency for tracking
                $track[strval($a->Ccy[0][0])] = strval($a->CcyNm[0][0]);
                //sort the array values alphabetically
                ksort($track);
                break;
            }
        }

        foreach ($track as $b => $c) {

            //variable to track currencies in datastore
            $isAvailable = false;
            foreach ($requiredCurrencies->children() as $d) {
                //fetch the status of the currency from datastore
                $fetchStatus = $requiredCurrencies->xpath('//' . $b . '/@isActive');

                //when the currency is in the datstore and available to service
                if ($d->getName() == $b and $fetchStatus[0][0] == 'yes') {
                    $isAvailable = true;
                    // echo "i am true!";
                    echo '<option class = "available" value = "' . $b . '">' . $b . ' - ' . $c . '</option>';
                    break;
                }
            }
            if (!($isAvailable)) {
                echo '<option class = "unavailable" value = "' . $b . '">' . $b . ' - ' . $c . '</option>';
            }
        }
    }
    ?>
    <div id='formInterface'>
        <form id='testAction'>
            <span id = 'actionLabel' >Action:
            </span>

            <input id='put' name='action' type="radio" value='put'>
            <label id = 'putLabel' for="put"> PUT</label>

            <input id='post' name='action' type="radio" value='post'>
            <label id = 'postLabel' for="post"> POST</label>

            <input id='del' name='action' type="radio" value='del'>
            <label id = 'deleteLabel' for="del"> DEL</label>

            <label id='selectLabel' for="cur">CUI:</label>
            <select name="cur" id="cur">
                <option id='hidden' hidden selected disabled>Select currency code</option>
                <?php
                //invoke selectCurrencies() to create the dropdown currency list
                selectCurrencies();
                ?>
            </select>
            <span class='hint'>
                <pre>
                    green-coloured currencies are available to service
                    red-coloured currencies are unavailable to service
                    and only they can be posted
                </pre>
            </span>
            <button id='btn'>Submit</button>
            <label id='responseLabel' for="response">Respone XML</label>

            <textarea id="response" cols="60" rows="20"></textarea>

        </form>
    </div>

</body>

</html>