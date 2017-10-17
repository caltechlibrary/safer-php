<?php
/**
 * safer_test.php - tests safer.php
 *
 * @author R. S. Doiel, <rsdoiel@caltech.edu>
 * 
 * Copyright (c) 2016, Caltech
 * All rights not granted herein are expressly reserved by Caltech.
 * 
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
 * 
 * 1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
 * 
 * 2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
 * 
 * 3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

define("SAFER_ALLOW_UNSAFE", true);
if (php_sapi_name() !== "cli") {
    /*
    echo "Must be run from the command line." . PHP_EOL;
    exit(1);
    */
    header("Content-Type: text/plain");
}
error_reporting(E_ALL | E_STRICT);
@date_default_timezone_set(date_default_timezone_get());

echo 'Loading safer.php' . PHP_EOL;
require_once('safer.php');

/**
 * Testing class creates an test object designed to have the
 * same method structure and calling behavoir as Golang's testing package.
 */
class Testing
{
  /**
   * truthyness - Testing if actual is equal to expected using the operator provided.
   *
   * @param $actual
   * @param $expected
   * @param $message
   * @param $operator
   */
  private function truthyness($actual, $expected, $message, $operator) 
  {
        switch ($operator) {
        case '==' :
            if ($actual == $expected) {
                return true;
            }
            throw new Exception($message);
            break;
        case '!=' :
            if ($actual != $expected) {
                return true;
            }
            throw new Exception($message);
        break;
        case '===' :
            if ($actual === $expected) {
                return true;
            }
            throw new Exception($message);
        break;
        case '!==' :
            if ($actual !== $expected) {
                return true;
            }
            throw new Exception($message);
        break;
        case '<' :
            if ($actual < $expected) {
                return true;
            }
            throw new Exception($message);
        break;
        case '>' :
            if ($actual > $expected) {
                return true;
            }
            throw new Exception($message);
        break;
        case '<=' :
            if ($actual <= $expected) {
                return true;
            }
            throw new Exception($message);
        break;
        case '>=' :
            if ($actual >= $expected) {
                return true;
            }
            throw new Exception($message);
        break;
        default:
            throw new Exception("Don't know what to do with " . $operator);
        break;
        }
  }

  /**
   * ok - Testing if value is a true  value, it is equivalent to 
   * Testing::equal(true, value, message);
   * @param $value - value to be compared with true.
   * @param $message - the message to use if an exception is found.
   * @return true if assertion OK otherwise it throws and exception with 
   * the $exception_message
   */
  public function ok($value, $message) 
  {
        return Testing::truthyness(true, $value, $message, '==');
  }

  /**
   * notOk - Testing if value is a NOT true  value, it is equivalent to 
   * Testing::equal(false, value, message);
   * @param $value - value to be compared with true.
   * @param $message - the message to use if an exception is found.
   * @return true if assertion OK otherwise it throws and exception with 
   * the $exception_message
   */
  public function notOk($value, $message) 
  {
        return Testing::truthyness(true, $value, $message, '!=');
  }

  /**
   * fail - Testing a failure. It is equal to
   * Testing::equal(false, true, message);
   * @param $message - the message to use if an exception is found.
   * @return true if assertion OK otherwise it throws and exception with 
   * the $exception_message
   */
  public function fail($message) 
  {
        return Testing::truthyness(true, false, $message, '==');
  }

  /**
   * isTrue - asserts $value is equal to true, it is equivalent to 
   * Testing::equal(true, value, message);
   * @param $value - value to be compared with true.
   * @param $message - the message to use if an exception is found.
   * @return true if assertion OK otherwise it throws and exception with 
   * the $exception_message
   */
  public function isTrue($value, $message) 
  {
        return Testing::truthyness(true, $value, $message, '==');
  }

  /**
   * isFalse - asserts $value is equal to false, it is equivalent to 
   * Testing::equal(false, value, message);
   * @param $value - value to be compared with true.
   * @param $message - the message to use if an exception is found.
   * @return true if assertion OK otherwise it throws and exception with 
   * the $exception_message
   */
  public function isFalse($value, $message) 
  {
        return Testing::truthyness(false, $value, $message, '==');
  }

  
  /**
   * equal - Testing shallow, coercive equality with the equal comparison 
   * operator ( == ).
   * @param $actual - the value your are evaluating
   * @param $expected - the expacted value
   * @param $message - the message pass to the exception
   * @return true or throw error with $message.
   */
  public function equal($actual, $expected, $message) 
  {
        if ($actual == $expected) {
            return true;
        }
        throw new Exception($message);
  }

  /**
   * notEqual - Testing shallow, coercive non-equality with the not equal
   * comparison operator ( !=  ).  
   *
   * @param  $actual
   * @param  $expected
   * @param  $message
   * @return true or throw error with $message.
   */
  public function notEqual($actual, $expected, $message) 
  {
        return Testing::truthyness($actual, $expected, $message, '!=');
  }
  
  /**
   * strictEqual -
   *
   * @param  $actual
   * @param  $expected
   * @param  $message
   * @return true or throw error with $message.
   */
  public function strictEqual($actual, $expected, $message) 
  {
        return Testing::truthyness($actual, $expected, $message, '===');
  }
  
  /**
   * strictNotEqual -
   *
   * @param  $actual
   * @param  $expected
   * @param  $message
   * @return true or throw error with $message.
   */
  public function strictNotEqual($actual, $expected, $message) 
  {
        return Testing::truthyness($actual, $expected, $message, '!==');
  }

  /**
   * notTrue
   * @param $actual
   * @param $message
   * @return true or throw error with $message.
   */
  public function notTrue($actual, $message) 
  {
        return Testing::truthyness($actual, true, $message, '!=');
  }


  /**
   * notFalse
   * @param $actual
   * @param $message
   * @return true or throw error with $message.
   */
  public function notFalse($actual, $message) 
  {
        return Testing::truthyness($actual, false, $message, '!=');
  }


  /**
   * strictNotTrue
   * @param $actual
   * @param $message
   * @return true or throw error with $message.
   */
  public function strictNotTrue($actual, $message) 
  {
        return Testing::truthyness($actual, true, $message, '!==');
  }


  /**
   * strictNotFalse
   * @param $actual
   * @param $message
   * @return true or throw error with $message.
   */
  public function strictNotFalse($actual, $message) 
  {
        return Testing::truthyness($actual, false, $message, '!==');
  }
}


function testIsFilename($testIt) 
{
    $testIt->ok(isValidFilename("three.txt"), "/one/two/three.txt should be valid");
    $testIt->ok(isValidFilename("/one/two/three.txt"), "/one/two/three.txt should be valid");
    $testIt->ok(!isValidFilename("../etc/passwd"), "../etc/passwd should not be accepted as a valid filename");
    $testIt->ok(!isValidFilename("../etc/passwdpoiweopirepoewripewroperiwporpweoiewrpoiwerpoierwpoirepwoweripoewpoewrpoewripeowripewroierwpoierwpopeowirpoewrirewpoweripewroipeworiewproierwpierwpoierwpoirwepoierwpoiwerpoewirpewirperwipoerirepoierpowiepoweierpoiewrpoewrirewp"), "../etc/passwd should not be accepted as a valid filename");

    return "OK";
}

function testSupportFunctions($testIt)
{
    // Testing basic GET args
    $_GET = array();
    $_GET["int"] = "1";
    $_GET["float"] = "2.1";
    $_GET["varname"] = "my_var_name";
    $_GET["html"] = "This is a <b>html</b>.";
    $_GET["text"] = "This is plain text.";
    $_GET["boolean"] = "true";
    $_GET["url"] = "http://library.caltech.edu";
    $_GET["email"] = "gobeavers@library.caltech.edu";
    $expected_map = array(
    "int" => "Integer",
    "float" => "Float",
    "varname" => "Varname",
    "html" => "HTML",
    "text" => "Text",
        "boolean" => "Boolean",
        "url" => "Url",
        "email" => "Email"
    );
    $results = defaultValidationMap($_GET);
    $testIt->ok($results, "Should get back an array for defaultValidationMap()");
    foreach ($expected_map as $key => $value) {
        $testIt->ok(isset($results[$key]), "Must have $key in results");
        $testIt->equal($results[$key], $expected_map[$key], "results != expected for [$key], got " . print_r($results, true));
    }
    foreach ($results as $key => $value) {
        $testIt->ok(isset($expected_map[$key]), "Unexpected $key in results" . print_r($results, true));
    }
    
    return "OK";
}

function testImprovedURLHandling($testIt) 
{
    $_GET = array("url" => "http://example.com");
    $expected = "http://example.com";
    $result = safer($_GET, array("url" => "url"));
    $testIt->equal($result['url'], $expected, "expected $expected");

    $_GET = array("url" => "www.example.com");
    $expected = "http://www.example.com";
    $result = safer($_GET, array("url" => "url"));
    $testIt->equal($result['url'], $expected, "expected $expected");

    return "OK";
}

function testFixHTMLQuotes($testIt) 
{
    $s = '<p>Testing of "quotes" in string.</p>';
    $result = fix_html_quotes($s);
    $expected = '<p>Testing of &quot;quotes&quot; in string.</p>';
    $testIt->equal($result, $expected, "Expected entities: " . $result);

    $s = 'Testing of "quotes" in <b>string</b>.';
    $result = fix_html_quotes($s);
    $expected = 'Testing of &quot;quotes&quot; in <b>string</b>.';
    $testIt->equal($result, $expected, "Expected entities: " . $result);


    $s = 'Testing of "quotes" before <a href="http://example.com">link</a>.';
    $result = fix_html_quotes($s);
    $expected = 'Testing of &quot;quotes&quot; before <a href="http://example.com">link</a>.';
    $testIt->equal($result, $expected, "Expected entities: " . $result);

    return "OK";
}


function testGETProcessing($testIt) 
{
    // Testing $_GET processing works
    $_GET = array(
    "one" => "1",
    "two" => "2.1",
    "three" => "my_var_name",
    "four" => "This is a string.",
        "five_six" => "this is five underscore six",
        "seven-eight" => "this is seven dash eight"
    );
    $expected_map = array(
    "one" => "1",
    "two" => "2.1",
    "three" => "my_var_name",
    "four" => "This is a string.",
        "five_six" => "this is five underscore six",
        "seven-eight" => "this is seven dash eight"
    );
    
    $results = safer($_GET);
    
    $testIt->ok($results, "Should have results from safer()");
    foreach ($expected_map as $key => $value) {
        $testIt->ok(isset($results[$key]), "Must have $key in results");
        $testIt->equal($results[$key], $expected_map[$key], "results != expected for [$key], got " . print_r($results, true));
    }
    foreach ($results as $key => $value) {
        $testIt->ok(isset($expected_map[$key]), "Unexpected $key in results" . print_r($results, true));
    }
    
    return "OK";
}

function testPOSTProcessing($testIt) 
{
    // Testing $_POST processing works
    $_POST = array(
    "one" => "1",
    "two" => "2.1",
    "three" => "my_var_name",
    "four" => "This is a string."
    );
    $expected_map = array(
    "one" => "1",
    "two" => "2.1",
    "three" => "my_var_name",
    "four" => "This is a string.",
    );
    
    $results = safer($_POST);
    
    $testIt->ok($results, "Should have results from safer()");
    foreach ($expected_map as $key => $value) {
        $testIt->ok(isset($results[$key]), "Must have $key in results");
        $testIt->equal($results[$key], $expected_map[$key], "results != expected for [$key], got " . print_r($results, true));
    }
    foreach ($results as $key => $value) {
        $testIt->ok(isset($expected_map[$key]), "Unexpected $key in results" . print_r($results, true));
    }
    
    return "OK";
}

function testSERVERProcessing($testIt) 
{
    // Testing $_POST processing works
    $_SERVER = array(
    "one" => "1",
    "two" => "2.1",
    "three" => "my_var_name",
    "four" => "This is a string."
    );
    $expected_map = array(
    "one" => "1",
    "two" => "2.1",
    "three" => "my_var_name",
    "four" => "This is a string.",
    );
    
    $results = safer($_SERVER);
    
    $testIt->ok($results, "Should have results from safeSERVER()");
    foreach ($expected_map as $key => $value) {
        $testIt->ok(isset($results[$key]), "Must have $key in results");
        $testIt->equal($results[$key], $expected_map[$key], "results != expected for [$key], got " . print_r($results, true));
    }
    foreach ($results as $key => $value) {
        $testIt->ok(isset($expected_map[$key]), "Unexpected $key in results" . print_r($results, true));
    }

    // Testing processing PATH_INFO against a known path structure.
    $term_code_regexp = "20[0-9][0-9][1-3]";
    $section_code_regexp = "[0-9][0-9][0-9][0-9][0-9]";
    $_SERVER['PATH_INFO'] = '/20142/33361';
    $results = safer(
        $_SERVER,
        array(
            "PATH_INFO" => '/' . $term_code_regexp . '/' . $section_code_regexp
        )
    );
    $testIt->ok($results, "Should have results from safeSERVER() for PATH_INFO");
    $testIt->ok($results['PATH_INFO'], "PATH_INFO should not be false");
    return "OK";
}

function testSafeStrToTime($testIt) 
{
    $s = "2014-01-01 00:00:00";
    try {
        $dt = saferStrToTime($s);
    } catch (Exception $exception) {
        $testIt->fail("Shouldn't get this exception: " . $exception);
    }
    
    $s = "bogus date here.";
    $exception_thrown = false;
    try {
        $dt = saferStrToTime($s);
    } catch(Exception $exception) {
        $exception_thrown = true;
    }
    $testIt->ok($exception_thrown, "Should have thrown an exception on converting $s");
    return "OK";
}

function testVarnameLists($testIt) 
{
    $s = "one,two,three";
    $r = makeAs($s, "varname_list");
    $testIt->equal($s, $r, "[$s] == [$r]");
    $e = 'one,two,three';
    $s = '$' . 'one,two,' . '$' . 'three';
    $r = makeAs($s, "varname_list");
    $testIt->equal($e, $r, "[$e] == [$r] for [$s]");
    $e = true;
    $s = 'true';
    $r = makeAs($s, 'boolean');
    $testIt->equal($e, $r, "[$e] == [$r] for [$s]");
    $e = true;
    $s = '1';
    $r = makeAs($s, 'boolean');
    $testIt->equal($e, $r, "[$e] == [$r] for [$s]");
    $e = false;
    $s = 'false';
    $r = makeAs($s, 'boolean');
    $testIt->equal($e, $r, "[$e] == [$r] for [$s]");
    $e = false;
    $s = '0';
    $r = makeAs($s, 'boolean');
    $testIt->equal($e, $r, "[$e] == [$r] for [$s]");
    $e = false;
    $s = 'blahblah';
    $r = makeAs($s, 'boolean');
    $testIt->equal($e, $r, "[$e] == [$r] for [$s]");
    
    return "OK";
}

function testPRCEExpressions($testIt) 
{
    $re = "\([0-9][0-9][0-9]\)[0-9][0-9][0-9]-[0-9][0-9][0-9][0-9]";
    $s = "(626)395-3405";
    $e = "(626)395-3405";
    $r = makeAs($s, $re, true);
    $testIt->equal($e, $r, "[$e] == [$r] for [$s]");

    $s = "(626)395-340592";
    $e = false;
    $r = makeAs($s, $re, true);
    $testIt->equal($e, $r, "[$e] == [$r] for [$s]");

    return "OK";
    
}

function testMakeAs($testIt) 
{
    $s = "one1";
    $e = "one1";
    $r = makeAs($s, "varname", false);
    $testIt->equal($e, $r, "[$e] == [$r] for [$s]");

    $s = 1;
    $e = false;
    $r = makeAs($s, "varname", false);
    $testIt->equal($e, $r, "[$e] == [$r] for [$s]");

    $s = "http://library.caltech.edu";
    $e = "http://library.caltech.edu";
    $r = makeAs($s, "Url", false);
    $testIt->equal($e, $r, "[$e] == [$r] for [$s]");

    $s = "htp://library.caltech.edu";
    $e = false;
    $r = makeAs($s, "Url", false);
    $testIt->equal($e, $r, "[$e] == [$r] for [$s]");

    $valid_email_examples = array(
        'gobeavers@library.caltech.edu',
        'niceandsimple@example.com',
        'very.common@example.com',
        'a.little.lengthy.but.fine@dept.example.com',
        'disposable.style.email.with+symbol@example.com',
        'other.email-with-dash@example.com',
        '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"@strange.example.com'
    );
    foreach ($valid_email_examples as $s) {
        $e = $s;
        $r = makeAs($s, "Email", false);
        $testIt->equal($e, $r, "[$e] == [$r] for [$s]");
    }
    $invalid_email_examples = array(
        '3@c@gobeavers@library.caltech.edu',
        'Abc.example.com',
        'A@b@c@example.com',
        'a"b(c)d,e:f;g<h>i[j\k]l@example.com',
        'just"not"right@example.com',
        'this is"not\allowed@example.com',
        'this\ still\"not\\allowed@example.com'
    );
    foreach ($invalid_email_examples as $s) {
        $e = false;
        $r = makeAs($s, "Email", false);
        $testIt->equal($e, $r, "[$e] == [$r] for [$s]");
    }

    if (function_exists("mb_detect_encoding") === TRUE) {
    $text=<<<EOT
Frédéric Hurlet, Université Paris-Ouest, Nanterre La Défense, will present "Spaces of Indignity. Being deprived of friendship of the prince and banned from court" to the Pre-Modern Mediterrean seminar series on April 21st.
EOT;

    $text_expected=<<<EOT
Fr&#233;d&#233;ric Hurlet, Universit&#233; Paris-Ouest, Nanterre La D&#233;fense, will present &quot;Spaces of Indignity. Being deprived of friendship of the prince and banned from court&quot; to the Pre-Modern Mediterrean seminar series on April 21st.
EOT;

    $result = makeAs($text, 'HTML', true);
    $testIt->equal($text_expected, $result, "\n[$text_expected]\n[$result]\n");

    // Christel Muller example.
    $text=<<<EOT
Christel Müller, Université Paris-Ouest Nanterre La Défense, will present "The 'common emporion of Greece': groups and subgroups of foreigners in Late Hellenistic Delos' to the Pre-Modern Mediterranean" seminar on Monday, April 20th.
EOT;

    $text_expected=<<<EOT
Christel M&#252;ller, Universit&#233; Paris-Ouest Nanterre La D&#233;fense, will present &quot;The &apos;common emporion of Greece&apos;: groups and subgroups of foreigners in Late Hellenistic Delos&apos; to the Pre-Modern Mediterranean&quot; seminar on Monday, April 20th.
EOT;
    $result = makeAs($text, 'HTML', true);
    $testIt->equal($text_expected, $result, "\n[$text_expected]\n[$result]\n");
    

    $text=<<<EOT
Professor Müller's paper aims at observing the changes that have affected, during the Late Hellenistic period (c. 150-50 BCE), the exceptional Delian 'social laboratory', an Athenian colony at this time according to Pierre Roussel: these changes concern the composition, but also the perception and self presentation of the diverse groups of foreigners that had their residence on the island (Athenians, Romans/Italians, other 'Greeks'...). The main documents here are the inscriptions, first the decrees of the so-called Athenian cleruchy, then later on the numerous dedications made by these groups collectively.
EOT;

    $text_expected=<<<EOT
Professor M&#252;ller&apos;s paper aims at observing the changes that have affected, during the Late Hellenistic period (c. 150-50 BCE), the exceptional Delian &apos;social laboratory&apos;, an Athenian colony at this time according to Pierre Roussel: these changes concern the composition, but also the perception and self presentation of the diverse groups of foreigners that had their residence on the island (Athenians, Romans/Italians, other &apos;Greeks&apos;...). The main documents here are the inscriptions, first the decrees of the so-called Athenian cleruchy, then later on the numerous dedications made by these groups collectively.
EOT;

    $result = makeAs($text, 'HTML', true);
    $testIt->equal($text_expected, $result, "\n[$text_expected]\n[$result]\n");
    

    $text=<<<EOT
Christel Müller is Professor of Greek History at the University of Paris Ouest Nanterre La Défense. Her main subjects of interest are: society and institutions of Hellenistic Greece, Boeotian epigraphy, the economy and society of colonisation (Black Sea). She is the author of D'Olbia à Tanaïs: Territoires et réseaux d'échanges dans la Mer Noire septentrionale aux époques classique et hellénistique (2010), the co-author of Archéologie historique de la Gréce antique, 3rd ed. (2014) and the co-editor of Les Italiens dans le monde grec (2002), Identités et cultures dans le monde méditerranéen antique (2002), Citoyenneté et participation à la basse époque hellénistique (2005) and Identité ethnique et culture matérielle dans le monde grec (2014).
EOT;

    $text_expected=<<<EOT
Christel M&#252;ller is Professor of Greek History at the University of Paris Ouest Nanterre La D&#233;fense. Her main subjects of interest are: society and institutions of Hellenistic Greece, Boeotian epigraphy, the economy and society of colonisation (Black Sea). She is the author of D&apos;Olbia &#224; Tana&#239;s: Territoires et r&#233;seaux d&apos;&#233;changes dans la Mer Noire septentrionale aux &#233;poques classique et hell&#233;nistique (2010), the co-author of Arch&#233;ologie historique de la Gr&#233;ce antique, 3rd ed. (2014) and the co-editor of Les Italiens dans le monde grec (2002), Identit&#233;s et cultures dans le monde m&#233;diterran&#233;en antique (2002), Citoyennet&#233; et participation &#224; la basse &#233;poque hell&#233;nistique (2005) and Identit&#233; ethnique et culture mat&#233;rielle dans le monde grec (2014).
EOT;

    $result = makeAs($text, 'HTML', true);
    $testIt->equal($text_expected, $result, "\n[$text_expected]\n[$result]\n");
    } else {
        return "OK, but skipped multi-byte tests as mb_detect_encoding() not defined";
    }
    return "OK";
}

function testSelectMultiple($testIt) 
{
    $_POST = array(
        'select_multiple' => array(
            '1',
            '2',
            'The Fox'
        )
    );

    $post = safer(
        $_POST,
        array(
        'select_multiple' => 'Array_Integers'
        )
    );

    $testIt->equal($post['select_multiple'][0], '1', 'First element should be "1"');
    $testIt->equal($post['select_multiple'][1], '2', 'Second element should be "2"');
    $testIt->equal(isset($post['select_multiple'][2]), false, "Third element should not be there. " . print_r($post, true));
    return "OK";
}

function testUTF2HTML($testIt) 
{
    if (function_exists("mb_detect_encoding") === FALSE) {
        return "Skipped, missing multi-byte functions";
    }
    
    $s = '<a href="#jim">Jim</a> said, ' . html_entity_decode('&ldquo;') . 'I' . 
        html_entity_decode('&apos;') . 's here now.' . html_entity_decode('&rdquo;');
    $e = '<a href="#jim">Jim</a> said, &#8220;I&apos;s here now.&#8221;';
    if (version_compare(PHP_VERSION, '5.4.3', '<')) {
        // PHP 5.3.3 translate this way.
        $e = '<a href="#jim">Jim</a> said, &ldquo;I&apos;s here now.&rdquo;';
    }
    $r = utf2html($s);
    $testIt->equal($e, $r, "[$e] != [$s]");
    /*FIXME: not sure a solution for this one yet. 
    // Strip \u009c, \u009d, \u0080
    $s = '&#195;&#162;&#194;\u0080&#194;\u009cPicturing Ovid in Pompeii&#195;&#162;&#194;\u0080&#194;\u009d Peter Knox (University of Colorado)';
    $e = '&#195;&#162;&#194;&#194;Picturing Ovid in Pompeii&#195;&#162;&#194;#194; Peter Knox (University of Colorado)';
    $r = utf2html($s);
    $testIt->equal($e, $r, "[$e] != [$s]");
    */

    $s = '<a href="mylink.html">My Link</a>';
    $e = '<a href="mylink.html">My Link</a>';
    $r = utf2html($s);
    $testIt->equal($e, $r, "[$e] != [$s]");

    $text=<<<EOT
Frédéric Hurlet, Université Paris-Ouest, Nanterre La Défense, will present "Spaces of Indignity. Being deprived of friendship of the prince and banned from court" to the Pre-Modern Mediterrean seminar series on April 21st.
EOT;

    $text_expected=<<<EOT
Fr&#233;d&#233;ric Hurlet, Universit&#233; Paris-Ouest, Nanterre La D&#233;fense, will present "Spaces of Indignity. Being deprived of friendship of the prince and banned from court" to the Pre-Modern Mediterrean seminar series on April 21st.
EOT;

    $result = utf2html($text);
    $testIt->equal($text_expected, $result, "\n[$text_expected]\n[$result]\n");

    $text=<<<EOT
Like promotion, loss of social position in Rome tends to exist only when it becomes visible and is inserted as such in public space a way or another. This lecture will focus on two political practices which meant for concerned senators a loss of credit,and thus of status: first, the official act by which the prince or a member of the imperial family would withdraw his friendship from a senator (the so called renuntiatio amicitiae); then, the way a senator could be prevented from visiting the prince (theinterdictio aulae). This lecture will present the sources for these two practices focusing on the more practical aspects and their spatial inscription.
EOT;

    $text_expected=<<<EOT
Like promotion, loss of social position in Rome tends to exist only when it becomes visible and is inserted as such in public space a way or another. This lecture will focus on two political practices which meant for concerned senators a loss of credit,and thus of status: first, the official act by which the prince or a member of the imperial family would withdraw his friendship from a senator (the so called renuntiatio amicitiae); then, the way a senator could be prevented from visiting the prince (theinterdictio aulae). This lecture will present the sources for these two practices focusing on the more practical aspects and their spatial inscription.
EOT;

    $result = utf2html($text);
    $testIt->equal($text_expected, $result, "\n[$text_expected]\n[$result]\n");


    $text=<<<EOT
Frédéric Hurlet is professor of History of the Roman World, University Paris Ouest Nanterre La Défense. He is Director of the Maison de l’Archéologie et de l’Ethnologie, René-Ginouvès. He published a few books and papers on the transition between Republic and Principate (first century BC-first century AD). He's working on the imperial power, the Roman aristocracy and the government of the Roman Empire.
EOT;

    $text_expected=<<<EOT
Fr&#233;d&#233;ric Hurlet is professor of History of the Roman World, University Paris Ouest Nanterre La D&#233;fense. He is Director of the Maison de l&#8217;Arch&#233;ologie et de l&#8217;Ethnologie, Ren&#233;-Ginouv&#232;s. He published a few books and papers on the transition between Republic and Principate (first century BC-first century AD). He's working on the imperial power, the Roman aristocracy and the government of the Roman Empire.
EOT;
   
    $result = utf2html($text);
    $testIt->equal($text_expected, $result, "\n[$text_expected]\n[$result]\n");
    
    // Christel Muller example.
    $text=<<<EOT
Christel Müller, Université Paris-Ouest Nanterre La Défense, will present "The 'common emporion of Greece': groups and subgroups of foreigners in Late Hellenistic Delos' to the Pre-Modern Mediterranean" seminar on Monday, April 20th.
EOT;

    $text_expected=<<<EOT
Christel M&#252;ller, Universit&#233; Paris-Ouest Nanterre La D&#233;fense, will present "The 'common emporion of Greece': groups and subgroups of foreigners in Late Hellenistic Delos' to the Pre-Modern Mediterranean" seminar on Monday, April 20th.
EOT;
    $result = utf2html($text);
    $testIt->equal($text_expected, $result, "\n[$text_expected]\n[$result]\n");
    

    $text=<<<EOT
Professor Müller's paper aims at observing the changes that have affected, during the Late Hellenistic period (c. 150-50 BCE), the exceptional Delian 'social laboratory', an Athenian colony at this time according to Pierre Roussel: these changes concern the composition, but also the perception and self presentation of the diverse groups of foreigners that had their residence on the island (Athenians, Romans/Italians, other 'Greeks'...). The main documents here are the inscriptions, first the decrees of the so-called Athenian cleruchy, then later on the numerous dedications made by these groups collectively.
EOT;

    $text_expected=<<<EOT
Professor M&#252;ller's paper aims at observing the changes that have affected, during the Late Hellenistic period (c. 150-50 BCE), the exceptional Delian 'social laboratory', an Athenian colony at this time according to Pierre Roussel: these changes concern the composition, but also the perception and self presentation of the diverse groups of foreigners that had their residence on the island (Athenians, Romans/Italians, other 'Greeks'...). The main documents here are the inscriptions, first the decrees of the so-called Athenian cleruchy, then later on the numerous dedications made by these groups collectively.
EOT;

    $result = utf2html($text);
    $testIt->equal($text_expected, $result, "\n[$text_expected]\n[$result]\n");
    

    $text=<<<EOT
Christel Müller is Professor of Greek History at the University of Paris Ouest Nanterre La Défense. Her main subjects of interest are: society and institutions of Hellenistic Greece, Boeotian epigraphy, the economy and society of colonisation (Black Sea). She is the author of D'Olbia à Tanaïs: Territoires et réseaux d'échanges dans la Mer Noire septentrionale aux époques classique et hellénistique (2010), the co-author of Archéologie historique de la Gréce antique, 3rd ed. (2014) and the co-editor of Les Italiens dans le monde grec (2002), Identités et cultures dans le monde méditerranéen antique (2002), Citoyenneté et participation à la basse époque hellénistique (2005) and Identité ethnique et culture matérielle dans le monde grec (2014).
EOT;

    $text_expected=<<<EOT
Christel M&#252;ller is Professor of Greek History at the University of Paris Ouest Nanterre La D&#233;fense. Her main subjects of interest are: society and institutions of Hellenistic Greece, Boeotian epigraphy, the economy and society of colonisation (Black Sea). She is the author of D'Olbia &#224; Tana&#239;s: Territoires et r&#233;seaux d'&#233;changes dans la Mer Noire septentrionale aux &#233;poques classique et hell&#233;nistique (2010), the co-author of Arch&#233;ologie historique de la Gr&#233;ce antique, 3rd ed. (2014) and the co-editor of Les Italiens dans le monde grec (2002), Identit&#233;s et cultures dans le monde m&#233;diterran&#233;en antique (2002), Citoyennet&#233; et participation &#224; la basse &#233;poque hell&#233;nistique (2005) and Identit&#233; ethnique et culture mat&#233;rielle dans le monde grec (2014).
EOT;

    $result = utf2html($text);
    $testIt->equal($text_expected, $result, "\n[$text_expected]\n[$result]\n");

    // NOTE: The micron should not cause the text to be changed. This test confirms the text is preserved by utf2html()
    $text=<<<EOT
Standard historiography of the church in Kyoto during the Christian Century (1549-1650) does not mention the remarkable leader Naito Julia. Julia, who was once the abbess of Jōdo convent, became a successful evangelist for the Jesuit mission in Japan. She founded and led a society of Christian women catechists, whom people called the Miyaco no bicuni [bikuni] ("nuns of Kyoto"). Between 1600 and 1612, Julia and her nuns preached, engaged in religious disputations, catechized, baptized hundreds of persons, and provided pastoral care for the new converts. Although none of the women's writings survive to tell us about their ideas and experiences, Fucan Fabian's 1607 Myōtei mondō suggests how they may have understood differences between Buddhism, Shinto, Confucianism, Taoism and Christianity.
EOT;

    $text_expected=<<<EOT
Standard historiography of the church in Kyoto during the Christian Century (1549-1650) does not mention the remarkable leader Naito Julia. Julia, who was once the abbess of Jōdo convent, became a successful evangelist for the Jesuit mission in Japan. She founded and led a society of Christian women catechists, whom people called the Miyaco no bicuni [bikuni] ("nuns of Kyoto"). Between 1600 and 1612, Julia and her nuns preached, engaged in religious disputations, catechized, baptized hundreds of persons, and provided pastoral care for the new converts. Although none of the women's writings survive to tell us about their ideas and experiences, Fucan Fabian's 1607 Myōtei mondō suggests how they may have understood differences between Buddhism, Shinto, Confucianism, Taoism and Christianity.
EOT;

    $result = utf2html($text);
    $testIt->equal($text_expected, $result, "\n[$text_expected]\n[$result]\n");
    
    return "OK";
}

function testAttributeCleaning($testIt) 
{
    $s = '<div><a href="mylink.html" title="fred" style="font-size:20">Fred</a></div>';
    $e = '<div><a href="mylink.html" title="fred">Fred</a></div>';
    $r = strip_attributes($s);
    $testIt->equal($e, $r, "[$e] != [$s]");

    $s = '<div><a href="mylink.html" title="fred" style="font-size:20">Fred</a></div>';
    $e = escape('<div><a href="mylink.html" title="fred">Fred</a></div>');
    $r = makeAs($s, "HTML");
    $testIt->equal($e, $r, "[$e] != [$r]");
    $pos = strpos($r, 'href=');
    $testIt->notEqual($pos, false, "$pos should not be false.");
    $pos = strpos($r, 'style=');
    $testIt->equal($pos, false, "[$e] != [$s]");

    $s = '<div><a href="javascript:alert(\'Something bad\');" title="fred" style="font-size:20">Fred</a></div>';
    $e = escape('<div><a title="fred">Fred</a></div>');
    $r = makeAs($s, "HTML");
    $testIt->equal($e, $r, "[$e] != [$r]");
    $pos = strpos($r, 'href=');
    $testIt->equal($pos, false, "$pos should be false.");
    $pos = strpos($r, 'style=');
    $testIt->equal($pos, false, "$pos should be false");
    return "OK";
}

function testSafeJSON($testIt) 
{
    $validation_map = array(
        'return_to_url' => '(\w+|\.|-|[0-9])+',
        'event_id' => 'Integer',
        'calendar_id' => 'Integer',
        'title' => 'text',
        'subtitle' => 'HTML',
        'summary' => 'HTML',
        'description' => 'HTML',
        'dates' => 'Text', 
        'start' => 'Text',
        'end' => 'Text',
        'venue' => 'Text',
        'building_code' => 'Text',
        'campus' => 'Text',
        'room' => 'Text',
        'address' => 'Text',
        'url' => 'url',
        'contact_phone' => 'Text',
        'sponsors' => 'Text',
        'cost' => 'Text',
        'rsvp_email' => 'email',
        'rsvp_url' => 'url',
        'ticket_url' => 'url',
        'categories' => 'Text',
        'audiences' => 'Text',
        'notes' => 'Text',
        'contact_email' => 'email',
        'feature_candidate' => 'Boolean');

    $badjson =<<<BAD_JSON
{"event_id":"913298","title":"<a href =\"javascript:whs(1)\">click<\/a>","subtitle":"","summary":"<script>function whs(val) { testIt(val); }</script>click<a  href =\"javascript:whs(1)\">click<\/a>","description":"<a  href =\"javascript:whs(1)\">click<\/a>","cost":"","contact_phone":"","contact_email":"","rsvp_email":"","rsvp_url":"","url":"","ticket_url":"","campus":"University Park","venue":"125th Anniversary Fountain","building_code":"","room":"1234","address":"125th Anniversary Fountain","feature_candidate":"0","username":"dd_064","name":"WhiteHat Audit Account","scratch_pad":"","created":"2014-11-06 12:52:50","updated":"2014-11-06 12:52:50","publication_date":"0000-00-00 00:00:00","parent_calendar_id":"32","parent_calendar":"USC Public Events","sponsors":[],"audiences":[],"schedule":"11\/25\/2014: 03:00 - 05:00","dates":"11\/25\/2014","occurrences":[{"start":"2014-11-25 03:00:00","end":"2014-11-25 05:00:59"}],"first_occurrence":"2014-11-25 03:00:00","last_occurrence":"2014-11-25 03:00:00","next_occurrence":"2014-11-25 03:00:00","categories":{"32":["Theater"]},"attachments":{"32":{"image_o":{"mime_type":"image\/jpeg","url":"https:\/\/web-app.library.caltech.edu\/event-images\/32\/913298\/whs_xss_test.jpg"}}},"status":{"32":{"status":"draft","calendar_id":"32"}},"start":"3:00","end":"5:00","error_status":"OK"}
BAD_JSON;

    $result = json_decode($badjson, true);
    $result = saferJSON($badjson, $validation_map, false);
    $testIt->ok(is_array($result), "Should get an array type back");
    $testIt->ok(is_integer($result['event_id']), "Should have an integer value for event_id");
    $testIt->equal($result['event_id'], 913298, "have an event id of 913298");
    $testIt->ok(is_string($result['title']), "title should be string " . gettype($result['title']));
    $testIt->equal($result['title'], "click", "title wrong.");
    $testIt->equal(strpos($result['summary'], "<script>"), false, "Should move script element");
    return "OK";
}

function testHREFCleaning($testIt) 
{
    $validation_map = array(
        "title" => "HTML"
       );
    $_POST = array(
        "title" => 'Injection <a href="javascript:alert(\"Something Bad\")">Testing</a>.'
       );
    $expected_result = array(
        "title" => 'Injection <a >Testing</a>.'
       );
    $result = safer($_POST, $validation_map);
    $testIt->equal($result['title'], $expected_result['title'], "Should have a clean href in title: ". $result['title']);

    $_POST = array(
        "title" => 'Injection <a href=' . "'" . 'javascript:alert("Something Bad")' . "'" . '>Testing</a>.'
       );
    $result = safer($_POST, $validation_map);
    $testIt->equal($result['title'], $expected_result['title'], "Should have a clean href in title: ". $result['title']);
    return "OK";
}

function testAnchorElementSantization($testIt) 
{
    $validation_map = array(
       "txt" => "HTML"
    );

    $badjson =<<<BAD_JSON
{"txt": "<a href=\"javascript:alert('test')\">click</a>"}
BAD_JSON;
    $result = saferJSON($badjson, $validation_map, false);
    $testIt->equal(strpos($result["txt"], "javascript"), false, "Javascript href should get removed.");
    $goodjson =<<<GOOD_JSON
{"txt": "<a href=\"http://example.com\">click</a>"}
GOOD_JSON;
    $result = saferJSON($goodjson, $validation_map, false);
    $testIt->ok(strpos($result["txt"], "http://example.com") !== false, "href should not get removed.");
    return "OK";
}

function testHTMLQuoteHandling($testIt) 
{
    $_GET = array('title' => '<p>Testing of "quotes"</p>');
    $result = safer($_GET, array('title' => 'HTML'));
    $testIt->equal($result['title'], '<p>Testing of &quot;quotes&quot;</p>', "Should convert quotes to entity: " . $result['title']);
    return "OK";
}

function testCleanScriptElements($testIt) 
{
    $raw = '<script>alert("Oops this is bad.");</script>This is a title.';
    $expect = 'alert("Oops this is bad.");This is a title.';
    $result = strip_tags($raw, SAFER_ALLOWED_HTML);
    $testIt->equal($result, $expect, "strip_tags() failed." . $result);

    // lib version should convert the " to &lquo; and &rquo;
    $expect = 'alert(&quot;Oops this is bad.&quot;);This is a title.';

    $_GET = array("title" => $raw);
    $result = safer($_GET, array('title' => 'HTML'));
    $testIt->equal($result['title'], $expect, 'Failed to correct ' . $raw);


    $_POST = array("title" => $raw);
    $result = safer($_POST, array('title' => 'HTML'));
    $testIt->equal($result['title'], $expect, 'Failed to correct ' . $raw);
    return "OK";
}

function testSaneUnicodeSupportPCRE($testIt) 
{
    $allowInternational = false;
    if (defined('PCRE_VERSION')) {
        if (intval(PCRE_VERSION) >= 7) { // constant available since PHP 5.2.4
                $allowInternational = true;
        }
    }
    $testIt->ok($allowInternational, "PCRE should support proper UTF-8, may need to compile with --enable-unicode-properties");
    return "OK";
}

function testServerAssociativeArray($testIt) 
{
    // Example Server associative array
    $_SERVER = array(
        "SCRIPT_URL" => "/m/feedback-fixing.php",
        "SCRIPT_URI" => "http://library.caltech.edu/m/feedback-fixing.php",
        "HTTP_HOST" => "library.caltech.edu",
        "HTTP_CONNECTION" => "keep-alive",
        "CONTENT_LENGTH" => "48",
        "HTTP_CACHE_CONTROL" => "max-age=0",
        "HTTP_ORIGIN" => "http://library.caltech.edu",
        "HTTP_UPGRADE_INSECURE_REQUESTS" => "1",
        "HTTP_USER_AGENT" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.95 Safari/537.36",
        "CONTENT_TYPE" => "application/x-www-form-urlencoded",
        "HTTP_ACCEPT" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
        "HTTP_REFERER" => "http://library.caltech.edu/m/feedback-fixing.php",
        "HTTP_ACCEPT_ENCODING" => "gzip, deflate",
        "HTTP_ACCEPT_LANGUAGE" => "en-US,en;q=0.8",
        "HTTP_COOKIE" => "__unam=27ba962-158887ada2a-1c500158-8; PHPSESSID=8rbs9blf9an0le5nsvbmpmk4t2; _ga=GA1.2.1705274370.1472593962",
        "PATH" => "/usr/bin:/bin",
        "SERVER_SIGNATURE" => "",
        "SERVER_SOFTWARE" => "Apache/2.2.19 (Unix) mod_ssl/2.2.19 OpenSSL/1.0.0d PHP/5.3.6",
        "SERVER_NAME" => "library.caltech.edu",
        "SERVER_ADDR" => "131.215.225.1",
        "SERVER_PORT" => "80",
        "REMOTE_ADDR" => "131.215.226.25",
        "DOCUMENT_ROOT" => "/usr/local/apache2/htdocs",
        "SERVER_ADMIN" => "websters@library.caltech.edu",
        "SCRIPT_FILENAME" => "/usr/local/apache2/htdocs/m/feedback-fixing.php",
        "REMOTE_PORT" => "58240",
        "GATEWAY_INTERFACE" => "CGI/1.1",
        "SERVER_PROTOCOL" => "HTTP/1.1",
        "REQUEST_METHOD" => "POST",
        "QUERY_STRING" => "",
        "REQUEST_URI" => "/m/feedback-fixing.php",
        "SCRIPT_NAME" => "/m/feedback-fixing.php",
        "PHP_SELF" => "/m/feedback-fixing.php",
        "REQUEST_TIME" => 1483475849,
    );

    //  First check using the default map
    $server = safer($_SERVER);
    foreach ($_SERVER as $key => $value) {
        $testIt->equal($server[$key], $value, "$key should be $value, got " . $server[$key]);
    }
    return "OK";
}

function testEnforceDefaultsTrue($testIt) {
    $a = safer(array(), [
        'name' => 'text',
        'id' => 'integer',
    ], false, true);
    if (isset($a['name']) === false || isset($a['id']) === false) {
        $testIt->fail('Missing name or id keys ' . print_r($a, true));
    }
    if ($a['name'] !== '') {
        $testIt->fail('name key should be set to an empty string "' . print_r($a['name'], true) . '"');
    }
    if ($a['id'] !== 0) {
        $testIt->fail('id key should be set to zero integer value' . print_r($a, true));
    }
    return "OK";
}


echo "Starting [safer_test.php]..." . PHP_EOL;

// Global Testing object
$testIt = new Testing();
$testIt->ok(function_exists("defaultValidationMap"), "Should have a defaultValidationMap function defined.");
$testIt->ok(function_exists("safer"), "Should have a safer function defined.");
$testIt->ok(function_exists("saferJSON"), "Should have a saferJSON function defined.");
$testIt->ok(function_exists("saferFilename"), "Should have a saferFilename");

echo "\tTesting testServerAssociativeArray: " .  testServerAssociativeArray($testIt) . PHP_EOL;
echo "\tTesting testIsFilename: " . testIsFilename($testIt) . PHP_EOL;
echo "\tTesting testAttributeCleaning: " . testAttributeCleaning($testIt) . PHP_EOL;
echo "\tTesting testHREFCleaning: " . testHREFCleaning($testIt) . PHP_EOL;
echo "\tTesting testSaneUnicodeSupportPCRE: " . testSaneUnicodeSupportPCRE($testIt) . PHP_EOL;
echo "\tTesting testCleanScriptElements: " . testCleanScriptElements($testIt) . PHP_EOL;
echo "\tTesting testImprovedURLHandling: " . testImprovedURLHandling($testIt) . PHP_EOL;
echo "\tTesting testFixHTMLQuotes: " . testFixHTMLQuotes($testIt) . PHP_EOL;
echo "\tTesting testHTMLQuoteHandling: " . testHTMLQuoteHandling($testIt) . PHP_EOL;
echo "\tTesting testSelectMultiple: " . testSelectMultiple($testIt) . PHP_EOL;
echo "\tTesting support functions: " . testSupportFunctions($testIt) . PHP_EOL;
echo "\tTesting get processing: " . testGETProcessing($testIt) . PHP_EOL;
echo "\tTesting post processing: " . testPOSTProcessing($testIt) . PHP_EOL;
echo "\tTesting server processing: " . testSERVERProcessing($testIt) . PHP_EOL;
echo "\tTesting safeStrToTime process: " . testSafeStrToTime($testIt) . PHP_EOL;
echo "\tTesting Varname Lists process: " . testVarnameLists($testIt) . PHP_EOL;
echo "\tTesting PRCE expressions process: " . testPRCEExpressions($testIt) . PHP_EOL;
echo "\tTesting testSafeJSON: " . testSafeJSON($testIt) . PHP_EOL;
echo "\tTesting testAnchorElementSantization: " . testAnchorElementSantization($testIt) . PHP_EOL;
echo "\tTesting testUTF2HTML: " . testUTF2HTML($testIt) . PHP_EOL;
echo "\tTesting testMakeAs: " . testMakeAs($testIt) . PHP_EOL;
echo "\tTesting testEnforceDefaultsTrue: " . testEnforceDefaultsTrue($testIt) . PHP_EOL;
echo "Success!" . PHP_EOL;
?>
