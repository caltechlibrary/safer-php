<?php
function mergeInput($method, $varname, $default) {
    if (isset($_POST[$varname])) {
        return $_GET[$varname];
    }
    if (isset($_GET[$varname])) {
        return $_GET[$varname];
    }
    return $default;
}

echo <<<EOT

   This is a dummy login for testing examples. It
   checks to see if the \$_GET['user'] is "jane.doe"
   and \$_GET['secret'] is "some-mighty-secret-thingy".

EOT;

$expected_login = "jane.doe";
$expected_secret = "some-mighty-secret-thingy";
$login = mergeInput("user", "");
$secret = mergeInput("secret", "");

header("Content-Type: text/plain");
if ($expected_login === $login &&$expected_secret === $secret) {
    echo "Welcome $login";
} else {
    header("HTTP/1.0 401 Unauthorized");
    echo "You were not expected";
}
?>
