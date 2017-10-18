
# Test Examples

Included with _safer-php_ is _saferclient.php_ and _TestRunner.php_ and a _demotest_ htdocs root.
To run a _TestRunner.php_ demo try the following from two Bash shells.


From the first shell run

```shell
    php -S localhost:8000 -t demotest
```

From the second run

```shell
    export TEST_URL="http://localhost:8000/login.php"
    export TEST_USERNAME="jane.doe"
    export TEST_SECRET="some-mighty-secret-thingy"
    php TestRunner.php example_testing
```

This should run the tests and output results successfully. Then try kill the php webserver
in the first shell and re-run `php TestRunner.php example_testing` from the second. What
do you see that changed?

