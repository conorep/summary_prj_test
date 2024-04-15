<?php
/**
 * test_creds.php provides specific data for testing - .gitignore will not include test_creds data.
 * @var $canURL String Canvas URL
 * @var $cID Number user ID
 * @var $assnID Number assignment ID
 * @var $uToken String user access token
 * @var $stuID Number student ID
 *
 * func_calls.php provides the functions necessary for retrieving data
 * @function apiCall takes three params: method, url, and data. data is not required.
 * @function prettyPrint
 */
include 'test_creds.php';
include 'func_calls.php';
$headers = ["Authorization: Bearer $uToken", 'Content-Type: application/json'];

//get a single assignment download
$getSub = $canURL."api/v1/courses/$cID/assignments/$assnID/submissions/$stuID";
printRes('GET', $getSub, NULL, 'ASSIGNMENT DATA');


//get a single assignment rubric
$getRub = $canURL."api/v1/courses/$cID/rubrics/$assnID";
printRes('GET', $getRub, NULL, 'RUBRIC DATA');

//get all rubrics from course
$getRubs = $canURL."api/v1/courses/$cID/rubrics";
printRes('GET', $getRubs, NULL, 'ALL RUBRIC DATA');

function printRes($method, $url, $data, $printStatement) {
    global $headers;

    $assnData = apiCall($method, $url, $data, $headers);
    $assnData = json_decode($assnData);
//    $assnData = prettyPrint($assnData);
    echo "$printStatement:\n\n";
    var_dump($assnData);
    echo "\n\n";
}