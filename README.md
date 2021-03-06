# safer-php

A tiny input vetting library for legacy PHP code.  Also includes examples
of adding minimal testing using saferclient.php to interact with a PHP
driven site.

# Retrofiting legacy PHP projects

A common problem in supporting legacy PHP is that old code may not do 
enough or appropriate validation and this leads to potential injection
problems (XSS and SQL).  To mitigate this you need to do three things

* At the start of the PHP file require safer.php
* Before PHP code is executed then run safer($_GET), safer($_POST), and safer($_SERVER) as needed.

This might look something like -

```PHP
	<?php
	require("../safer-php/safer.php");
	$get = safer($_GET); 
	$post = safer($_POST);
	
	// the rest of the old should now work safer.
```

If you need to validated an uploaded filename you might do something like -

```PHP
    <?php
    // Get the filename from the $_FILES assoc array.
    $safeFilename = $_FILES['myupload']['name'];
    if ($safeFilename === false) {
        die('Not a valid filename.');
    }
```

# Using in new projects

When using safer in new projects you should provide an explicit validation
map.  This way we will not be vunerable to injected variables caused by
unsafe use of extract.

In this example their are three supported parameters - id, search, callback 
which are an Integer, Text and Varname respectively. Here's how you would
defined the validation map and then use it with your code.

```PHP
	<?php
	

	// Just some place holder code to indicate that you've already established a MySQL connection
	openMySQLConnection();

	require("/usr/local/apache2/htdocs/safer-php/safer.php");
	
	// Make a validation map
	$validation_map = array(
		"id" => "Integer",
		"search" => "Text",
		"callback" => "Varname"
	);
	
	// extract the $_GET safer validated against $validation_map
	$myGET = safer($_GET, $validation_map);

	// Now you're ready to use them.  If a field wasn't available it will be set to false
	if ($myGET["id"] !== false) {
		// build your query safer
		$sql = "SELECT name, email FROM contacts WHERE id = " . 
		$myGET["id"];
	} else if ($myGET['search'] !== false) {
		$sql = "SELECT name, email FROM contacts WHERE (name LIKE \"" . 
			$myGET["search"] . "\" OR email LIKE \"" . $myGET["search"] . "\"";
	}

	// Process your SQL safer
	$qry = mysql_query($sql);
	$users = mysql_fetch_assoc($qry);

	if ($myGET["callback"] !== false) {
		header("Content-Type: application/javascript");
		echo $callback . '(' . json_encode($users,  true) . ')';
	} else {
		header("Content-Type: application/json");
		echo json_encode($users, true);
	}
	?>
```

