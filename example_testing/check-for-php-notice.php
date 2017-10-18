<?php
/**
 * check-for-php-notice.php is an example of retrieve a PHP page from http://example.org/login.php
 * and see if any PHP notices show up in the retrieved page.
 */

if (php_sapi_name() != "cli") {
    echo "Must be run from the command line." . PHP_EOL;
    exit(1);
}

require_once("saferclient.php");

function mergeEnv($varname, $default) {
    $s = getenv($varname);
    if ($s === FALSE || $s === "") {
        return $default;
    }
    return $s; 
}

//NOTE: $testURL, $testUsername, $testSecert would normally point to a real host and account info to test with.
$testURL = mergeEnv("TEST_URL", "https://example.org/login.php");
$testUsername = mergeEnv("TEST_USERNAME", "jane.doe");
$testSecret = mergeEnv("TEST_SECRET", "some-mighty-secret-thingy");


echo<<<EOT

Started check-for-php-notice.php

NOTE:

This is an example test file using saferHttpGet() and saferHttpPost(). These tests
should fail unless you've set up the environment to point to real values. E.g. 
    
    export TEST_URL="$testURL"
    export TEST_USERNAME="$testUsername"
    export TEST_SECERT="$testSecret"

and have a web service returning a 200 from a GET and POST of the fields
user and secret.

EOT;

// Check the login page without any parameters
$response = saferHttpGet($testURL, array(
    "user" => $testUsername,
    "secret" => $testSecret
));
if ($response["error"] != "") {
    echo "Error http get" .  print_r($response, true) .  PHP_EOL;
    exit(1);
}
if ($response["status"] != "200") {
    echo "Unexpected http status " . $response["status"] . PHP_EOL;
    exit(1);
}

// Check the response for PHP "Notice" lines
$lines = explode($response["content"], "\n");
$i = 0;
foreach ($lines as $line) {
    if (strpos($line, "<b>Notice</b>") !== FALSE) {
       echo "NOTICE: $line"; 
       $i++;
    }
}

if ($i > 0) {
    echo "Found $i notices in response\n";
    exit(1);
}

// Now submit a login and see what response we get if they have notices
$response = saferHttpPost($testURL, array(
    "user" => $testUsername,
    "secret" => $testSecret
));
if ($response["error"] !== "") {
    echo "Error http get" .  print_r($response, true) .  PHP_EOL;
    exit(1);
}
// Check the response for PHP "Notice" lines
$lines = explode($response["content"], "\n");
$i = 0;
foreach ($lines as $line) {
    if (strpos($line, "<b>Notice</b>") !== FALSE) {
       echo "NOTICE: $line"; 
       $i++;
    }
}
if ($i > 0) {
    echo "Found $i notices in response\n";
    exit(1);
}

// Shouldn't reach here unless $testURL, $testUsername and $testPassword were
// setup to something real in the environment.
echo "OK!" . PHP_EOL;
?>
