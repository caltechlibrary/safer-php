
# Example Tests

This directory contains some example tests you might run against a PHP site your developing. This 
is really is just a starting point. The key things provided are an example TestRunner.php 
two example PHP test files. Notice that there is no real frame work. Tests are just PHP code.
If you have a big complex PHP project there are lots of test frameworks you can choose from but
if you site is small or you're trying to rangle in legacy PHP code then skipping a frame work
can be an advantage is no assumptions are made about the existing code base.  the file
_saferclient.php_ provides simple CURL wrappers for "getting" and "posting" to a PHP based
site.

From the root of the repository try the following:

```shell
    php example_testing/TestRunner.php
    php example_testing/TestRunner.php example_testing/stuff/stuff.php
    php example_testing/TestRunner.php example_testing/check-for-php-notice.php
    php example_testing/TestRunner.php example_testing/stuff
    php example_testing/TestRunner.php example_testing
```



