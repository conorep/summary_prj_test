<?php
/**
 * test_creds.php provides specific data for testing - .gitignore will not include test_creds data.
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
echo "<h3><u>INSTRUCTOR - VERGIL GUNCH, ANT101</u></h3>\n\n";
getReqdData();

$headers[0] = "Authorization: Bearer $stuToken";
$stuID = 'self';
echo "\n\n\n\n\n\n<h3><u>STUDENT - SUSAN B ANTHONY, ANT101</h3></u>\n\n";
getReqdData();

function printRes($method, $url, $data, $printStatement) {
    global $headers;

    $assnData = apiCall($method, $url, $data, $headers);
    $assnData = json_decode($assnData);
    echo "$printStatement:\n\n";
    print_r($assnData);
    echo "\n\n";
}

function getReqdData() {
	global $canURL, $cID, $assnID, $rubID, $stuID;

    echo "<u>Get a single assignment download:</u>\n";
	$getSub = $canURL."api/v1/courses/$cID/assignments/$assnID/submissions/$stuID".
        "?include[]=assignment&include[]=full_rubric_assessment";
	printRes('GET', $getSub, NULL, 'ASSIGNMENT DATA');

    echo "<u>Get a single assignment rubric:</u>\n";
    $getRub = $canURL."api/v1/courses/$cID/rubrics/$rubID";
	printRes('GET', $getRub, NULL, 'RUBRIC DATA');

    echo "<u>Get all rubrics from course:</u>\n";
    $getRubs = $canURL."api/v1/courses/$cID/rubrics";
	printRes('GET', $getRubs, NULL, 'ALL RUBRIC DATA');
}