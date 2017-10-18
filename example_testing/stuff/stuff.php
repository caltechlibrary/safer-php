<?php
$scriptname = "stuff.php";
if (isset($_SERVER["SCRIPT_NAME"])) {
    $scriptname = $_SERVER["SCRIPT_NAME"];
}
echo <<<EOT

NOTE (running as $scriptname): 

    This is an example test always succeeds. I.e. doesn't return a non-zero
    exit code.
    
    It can be run directly or via `php TestRunner.php`. By relying
    on `exit(1);` for failure we can treat tests as simple programms.
    No framework to learn, no match classes to hide things. You can
    run checks with simple if expressions, send test output with simple
    print or echo expressions.


EOT;

echo 'Test stuff.php, Successful' . PHP_EOL;
?>
