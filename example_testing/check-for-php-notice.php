<?php
/**
 * check-for-php-notice.php is an example of retrieve a PHP page from http://example.org/login.php
 * and see if any PHP notices show up in the retrieved page.
 */
require_once("saferclient.php");
if (php_sapi_name() != "cli") {
    echo "Must be run from the command line." . PHP_EOL;
    exit(1);
}

echo "Starting check-for-php-notice.php at https://example.org/login.php" . PHP_EOL;

// Check the login page without any parameters
$response = saferHttpGet("https://example.org/login.php");
if ($response["error"] != "") {
    echo "Error http get" .  print_r($response, true) .  PHP_EOL;
    exit(1);
}
if ($response["status"] != "200") {
    echo "Unexpected http status " . $response["status"] . PHP_EOL;
    exit(1);
}
// Check the response for PHP "Notice" lines
$lines = split($response["content"], "\n");
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
$response = saferHttpPost("https://example.org/login.php", array(
 "user" => "jane.doe",
 "password" => "some-mighty-secret-thing"
));
if ($response["error"] != "") {
    echo "Error http get" .  print_r($response, true) .  PHP_EOL;
    exit(1);
}
// Check the response for PHP "Notice" lines
$lines = split($response["content"], "\n");
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
echo "OK" . PHP_EOL;
?>
