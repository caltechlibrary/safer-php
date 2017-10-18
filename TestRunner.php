<?php
if (php_sapi_name() !== "cli") {
    echo "Must be run from the command line!";
    exit(1);
}

function listdir($p) {
  $p = realpath($p);
  $results = array();
  if (is_file($p)) {
    return array($p);
  }
  $dh = opendir($p);
  while ($fname = readdir($dh)) {
    if ($fname[0] !== "."  && $fname !== "..") {
      $results[] = realpath($p ."/" . $fname);
    }
  }
  closedir($dh);
  return $results;
}

/**
 * PHPFileList() takes a starting path and returns a list of php files
 *
 * @param $p the starting path
 * @return a list of php file paths (maybe empty if none found)
 */
function PHPFileList($p) {
  $results = array();
  $p = realpath($p);
  if (is_file($p)) {
    return array(realpath($p));
  }
  if (is_dir($p)) {
    $queue = listdir($p);
    while ($queue && count($queue) > 0 ) {
        $entry = array_pop($queue);
        if (is_file($entry)) {
          $ext = pathinfo($entry, PATHINFO_EXTENSION);
          if ($ext === "php") {
            $results[] = $entry;
          }
        }
        if (is_dir($entry) && strpos($entry, $p) === 0 && count($entry) > 0) {
          $subDir = listdir($entry);
          foreach ($subDir as $entry) {
            if ($entry) {
              $queue[] = realpath($entry);
            }
          }
        }
        $queue = array_unique($queue);
    }
  }
  return $results;
};

// TestPHPFileList() is an example function for testing PHPFileList()
function TestPHPFileList() {
  $l1 = PHPFileList(".");
  if (count($l1) !== 10) {
    echo "Expected 10 entries, got " . (count($l1)) . PHP_EOL;
    echo "    " . print_r($l1, true) . PHP_EOL;
    exit(1);
  }

  $l2 = PHPFileList("example_testing");
  if (count($l2) !== 2) {
    echo "Expected 2 entries, got " . (count($l2)) . PHP_EOL;
    echo "    " . print_r($l2, true) . PHP_EOL;
    foreach (listdir("example_testing") as $line) {
      echo $line . PHP_EOL;
    }
    exit(1);
  }
}


function TestRunner($thisFilename, $args) {
  if (count($args) === 0) {
    $args = array(
      "."
    );
  }
  foreach ($args as $arg) {
    $includeList = PHPFileList($arg);
    foreach ($includeList as $phpFile) {
      if ($thisFilename !== $phpFile) {
          echo "Trying $phpFile" . PHP_EOL;
         include($phpFile);
      }
    }
  }
}

if (isset($argv) && count($argv) > 1) {
    // array is a FIFO so we use shift instead of pop
    $thisFilename = realpath(array_shift($argv));
    echo "Running ". basename($thisFilename) . PHP_EOL;
    TestRunner($thisFilename, $argv);
    echo "Tests completed." . PHP_EOL;
} else {
    //echo "Testing the PHPFileList() function" . PHP_EOL;
    //TestPHPFileList();
    //echo "Success!" . PHP_EOL;
    echo "USAGE: php TestRunner.php PATH_TO_TESTS_YOU_WANT_TO_RUN" . PHP_EOL;
}
?>
