<?php
/**
 * test_creds.php provides specific data for testing
 * @var $canURL String Canvas URL
 * @var $cID Number user ID
 * @var $assnID Number assignment ID
 * @var $rubID Number rubric ID
 * @var $uToken String instructor user access token
 * @var $stuID Number|String student ID
 * @var $stuToken String student user access token
 *
 * func_calls.php provides the functions necessary for retrieving data
 * @function apiCall takes three params: method, url, and data. data is not required.
 * @function prettyPrint
 */
include './creds/test_creds.php';
include 'func_calls.php';
$headers = ["Authorization: Bearer $uToken", 'Content-Type: application/json'];
echo "<h3><u>INSTRUCTOR - VERGIL GUNCH, ANT101</u></h3>";
getReqdData();

//$headers[0] = "Authorization: Bearer $stuToken";
//$stuID = 'self';
//echo "\n\n\n\n\n\n<h3><u>STUDENT - SUSAN B ANTHONY, ANT101</h3></u>\n\n";
//getReqdData();

/**
 * A simple 'print response' function utilizing returnRes.
 * @param $method String GET, POST
 * @param $url String
 * @param $data array|null
 * @param $printStatement String statement shown before printing the query response data
 * @return void
 */
function printRes(string $method, string $url, array $data, string $printStatement) {
    $assnData = returnRes($method, $url, $data);
    echo "$printStatement:\n\n";
    print_r($assnData);
    echo "\n\n";
}

/**
 * A simple 'get and build HTML from response' function utilizing returnRes.
 * @param array $resURL
 * @return string
 */
function buildRes(array $resURL): string {
	$printedRes = json_encode($resURL, JSON_PRETTY_PRINT);
	return
		"<div id='centerBtn'><button type='button' id='jsonShower'>Show JSON data?</button></div>
		 <div class='formatJson'>
			<div class='initHide'><u>SUBMISSION DATA:</u><pre>$printedRes</pre></div>
		 </div>";
}

/**
 * A simple 'get data, return response' function utilizing apiCall.
 * @param $method String GET, POST
 * @param $data array|null
 * @param null $extraOpts
 * @return bool|mixed|string
 */
function returnRes(string $method, string $url, array $data = NULL, $extraOpts = NULL, bool $jsonEncodeIt = true) {
    global $headers;

    $assnData = apiCall($method, $url, $data, $headers, $extraOpts);
    if($jsonEncodeIt) {
        return json_decode($assnData, true);
    }
    return $assnData;
}

function buildCanUrl(): string {
	global $canURL, $cID, $assnID, $stuID;
	
	return $canURL."/api/v1/courses/$cID/assignments/$assnID/submissions/$stuID".
		"?include[]=assignment&include[]=full_rubric_assessment";
}

function getReqdData() {
	global $canURL, $headers;

    // get from res... .attachments.preview_url
	$getSub = buildCanUrl();
	$resURL = returnRes('GET', $getSub);
    echo buildRes($resURL);
    $thisResURL = $resURL['attachments'][0]['preview_url'];
    $getPreview = $canURL.$thisResURL;
    echo "<div><div class='conts'><u>PREVIEW URL:</u><br><div><i>$getPreview</i></div></div><br>";

    $headers[1] = 'Content-Type: text/html';
    $theRedirect = returnRes('GET', $getPreview, NULL, NULL, false);
    echo "<div class='conts'><u>REDIRECT RETURN (don't click link - collecting href):</u><br>".
		"<div><b><i>$theRedirect</i></b></div></div><br>";
    $headers[1] = 'Content-Type: text/plain';

	// build proper URI string
    $redReturn = explode('href="', $theRedirect)[1];
	$redReturn = explode('">', $redReturn)[0];
	$redReturn = explode('/view', $redReturn)[0];
	$redReturn .= '/annotated.pdf';
    echo "<div class='conts'><u>REDIRECT URL (TUNED UP):</u><br><div><i>$redReturn</i></div></div><br>";

    // POST to the htmlReturn redirect URL. should get 'Accepted' as return data.
    $startAnnoBuild = returnRes('POST', $redReturn, NULL, NULL, false);
	echo "<div class='conts'><u>WAS 'POST' QUERY ACCEPTED OR NOT?</u><br><div><b><i>$startAnnoBuild</i></b></div></div><br>";
	
	if($startAnnoBuild === 'Accepted') {
		$headers[1] = 'Content-Type: application/json';
		echo "<div class='conts'><u>WAITING FOR DOC READY:</u>";
		echo checkingReady($redReturn, 0)."</div>";
	} else {
		echo "<br>Canvas said we can't build this annotated PDF - something went wrong.";
	}
	echo "</div>";
}

function checkingReady(String $annoReady, int $numTries) {
	if($numTries < 50) {
		$readyCheckStr = $annoReady . '/is_ready';
		$checkReady = returnRes('GET', $readyCheckStr);
		if($checkReady && $checkReady['ready'] === true) {
			$totalTime = $numTries * 150 . '';
			echo "<div><i>TRY #$numTries</i></div>";
			echo "<div><b>Document preparation took ~$totalTime milliseconds.</b></div>";
			echo "<br><a id='getThisFile' href='$annoReady'>DOWNLOAD FILE</a>";
		} else {
			if($numTries === 0) {
				echo "<div><i>TRY  #$numTries</i></div><div>...</div>";
			}
			usleep(150);
			checkingReady($annoReady, $numTries+1);
		}
	} else {
		echo "<br><h4>Ah, no good. Bummer!</h4>";
	}
}


function printReqdData() {
	global $canURL, $cID, $rubID;
	$getSub = buildCanUrl();
	echo "<u>Get a single assignment download:</u>\n";
	printRes('GET', $getSub, NULL, 'ASSIGNMENT DATA');
	$getRub = $canURL."/api/v1/courses/$cID/rubrics/$rubID";
	echo "<u>Get a single assignment rubric:</u>\n";
	printRes('GET', $getRub, NULL, 'RUBRIC DATA');
	$getRubs = $canURL."/api/v1/courses/$cID/rubrics";
	echo "<u>Get all rubrics from course:</u>\n";
	printRes('GET', $getRubs, NULL, 'ALL RUBRIC DATA');
}