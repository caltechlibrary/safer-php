
# Validation Maps Overview

Validation maps are a simple key (name are validating) and value (data type) as value associative array.
Safer support some default types as well as regexp matching. If a field (e.g. $_GET) processed against a validation
map does not conform then the field will not be return by the various safer functions (e.g. safer($_GET), safer($_POST), saferJSON($json_src)).

```php
    <?php
    require('safer-php/safer.php');
    $validation_map = array(
       "fist_name" => "Text",
       "favorite_integer" => "Integer",
       "fancy_message" => "HTML"
    );
    $some_json_string = json_encode(array(i
        "first_name" => "Fred", 
        "favorite_integer" => "this is not a number", 
        "fancy_message" => "<script>console.log('hello world!');</script><p>OK HTHML now"));
    // $safe_json will not have favorite_integer since it is not an integer
    // and will only have '<p>OK HTML now' for fancy_message.
    $safer_json = saferJSON($some_json_string, $validation_map);
```

When _safer.php_ was originally created it was used to shim  support for surpressing XSS content attacks in old web forms.
Since then it has been improved as a small library to use generally in making input sanitization easier. Long run _safer.php_
will require validation maps for all the safe functions but in this implementation you can allow suppport use of *safer($_GET)*, *safer($_POST)*,
*safer($_SERVER)* and *safer($_SESSION)* without a validation map by defining SAFER_ALLOW_UNSAFE value in before loading the _safer.php_
file.  This is NOT recommended practice. Provide validation maps for all new PHP code where you use _safer.php_ functions!

## The basics validation types

+ integer
+ float
+ boolean
+ text
+ HTML
+ email
+ url
+ varname
+ varname_dash (a "-" is allowed in a varname)
+ varname_list
+ array_Text
+ array_Integer
+ Any regular expressions supported by the preg_* PHP functions.




