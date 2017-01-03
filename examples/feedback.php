<?php 
/********************************************************************************
 * This is an example of an old feedback form retrofitted with safer.php
 ********************************************************************************/
require('../safer.php');
// Form uses $_SERVER, $_SESSION and $_POST, setup safer copies.
$_post = array();
if (isset($_POST)) {
    $post = safer($_POST);
}
// In this rest of this file rename $_POST to safer $_post
/** REST of form is the same as before **/

/*header("Expires: Thu, 17 May 2001 10:17:17 GMT");
header("Last-Modified: " .gmdate("D,d M Y H:i:s") . "GMT"));*/
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");
?>

<?php
function checkEmail($email) 
{
    /*
   if(eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $email)) 
   {
      return FALSE;
   }

   list($Username, $Domain) = split("@",$email);

   if(getmxrr($Domain, $MXHost)) 
   {
      return TRUE;
   }
   else 
   {
      if(fsockopen($Domain, 25, $errno, $errstr, 30)) 
      {
         return TRUE; 
      }
      else 
      {
         return FALSE; 
      }
   }
   */
   return TRUE;
}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/general_page_php.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Mobile Feedback</title>
<!-- InstanceEndEditable -->
<?php $baseURL = $_SERVER['DOCUMENT_ROOT']."/common/" ?>
<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
<!--
td p{ margin-top: 0px;}
.contact {margin-right: 30px; font-weight: bold; color: #666;}

-->
</style>
<!-- InstanceEndEditable -->
</head>

<body>
<!-- InstanceBeginEditable name="body content" -->
<h3>Have Feedback? </h3>

	<!-- online feedback form -->
	<?php if ( $_POST['submitted'] == 'true' && empty($_POST['name']) ): ?>
	<p><font color="#FF0000">Missing Your Name</font></p>    <?php endif; ?>
    <?php if ( $_POST['submitted'] == 'true' && empty($_POST['email']) ): ?>
    <p><font color="#FF0000">Missing Your E-Mail Address</font></p>   <?php endif; ?>
	
    <?php if ( $_POST['submitted'] == 'true' && empty($_POST['validator']) ): ?>
    <p><font color="#FF0000">Missing security code</font></p>   <?php endif; ?>
    <?php if ( $_POST['submitted'] == 'true' && $_POST['email'] <> '' && (checkEmail($_POST['email']) == FALSE)): ?> 
	<p><font color="#FF0000">E-mail entered is not valid.</font></p>  <?php endif; ?>

    <?php if ($_POST['submitted']== 'true' && $_POST['validator'] <> '' && $_POST['validator'] <> $_SESSION['IMAGE_CODE']): ?>
    <p><font color="#FF0000">Invalid security code.</font></p> 
	
	<?php // for testing 
	//echo "validator = ", $_POST['validator'], "\n"; 
	//echo " IMAGE_CODE = ", $_SESSION['IMAGE_CODE'];
	?>
    <?php endif; ?>

    <?php if ( $_POST['submitted'] <> 'true' || empty($_POST['name']) || empty($_POST['email']) || 
	checkEmail($_POST['email'])== FALSE || empty($_POST['validator']) || $_POST['validator'] <> $_SESSION['IMAGE_CODE']): ?>
   
 <!-- <p> (<span class="redasterisk"> *</span>) Denotes a Required Field.<p/> -->
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="form_fields">
 <!-- <p> Your Name (<span class="redasterisk"> *</span>):<br /> -->
  <p> Your Name <label><small>(required)</small></label>:<br />
      <input name="name" size="45" value="<?php echo $_POST['name']; ?>" /> 
  </p>
  <!--<p>Your E-Mail Address (<span class="redasterisk"> *</span>):<br /> -->
  <p> Your E-Mail Address <label><small>(required)</small></label>:<br />
      <input name="email" size="45" value="<?php echo $_POST['email']; ?>" />
  </p>
  <p>
    <input name="copy" type="checkbox" value="copy" <?php if ( $_POST['copy']== "copy" ) { echo "checked" ;} ?> /> 
    Send me a copy of this email
  </p>
<p>Please enter these characters 
  <label><small>(required)</small></label>:<br />
<input type="text" name="validator" id="validator" size="20" />
<img src="/about/random.php" alt="secure code" width="80" height="20" vspace="1" align="top" />
</p>


  <p>Comments:<br />
      <textarea name="comments" rows="10" cols="40"><?php echo $_POST['comments']; ?></textarea>
  </p>

<input type="hidden" name="submitted" value="true" />
  <blockquote>
    <p>
        <input type="submit" value="Send" /> 
        |
        <input type="reset" value="Clear Form" />
    </p>
  </blockquote>
</form>
      <?php endif; ?>

<?php 
if ( $_POST['submitted'] == 'true' && !empty($_POST['validator']) && $_POST['validator'] == $_SESSION['IMAGE_CODE']
	&& $_POST['name'] <> '' && $_POST['email'] <> '' && checkEmail($_POST['email'])<> FALSE)
{
   define (NL, "<BR />\n");
	echo ("<h4>Your feedback has been submitted.</h4>\n");

    unset($_SESSION['IMAGE_CODE']); //**************************************
    /* session_destroy(); */
   	$timestamp = date("F dS, Y h:i:s a");
	
	echo ("<P>\n");
	echo ("Time: " . $timestamp . NL);
	echo ("Name: " . $_POST['name'] . NL);
	echo ("Email: " . $_POST['email'] . NL);
	echo ("Comments: <br><pre>" . $_POST['comments'] . NL);
	echo ("</pre></p><p>");
	echo ('<a href="/m"> Mobile home</a>');
        echo ("</P></blockquote>");

	$output = "Time: " . $timestamp . "\n";
	$output .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
	$output .= "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
	$output .= "Name: " . $_POST['name'] . "\n";
	$output .= "Email: " . $_POST['email']. "\n";
	$output .= "URL: " . $_POST['url']. "\n";
	$output .= "Comments: " . "\n". $_POST['comments']. "\n";
	$bcc = "kbuxton@library.caltech.edu,";
	if ( $_POST['copy']== "copy" ) { $bcc .= $_POST['email']; }
//mail("library@caltech.edu ", "Caltech Library Services Mobile Feedback", $output, "From: ".$_POST['email']."\r\n"."Bcc: ".$bcc."\r\n");
print("DEBUG <pre>"); // DEBUG
print("library@caltech.edu "); // DEBUG
print("Caltech Library Services Mobile Feedback"); // DEBUG
print($output);// DEBUG
printf("From: ".$_POST['email']."\r\n"."Bcc: ".$bcc."\r\n" );// DEBUG
print("</pre>"); // DEBUG

} else {
    print("DEBUG form not submitted");// DEBUG
    print("DEBUG<pre>");// DEBUG
    print(json_encode($_SESSION)); // DEBUG
    print(PHP_EOL);// DEBUG
    print(json_encode($_POST));
    print("</pre>");// DEBUG
}
?>
<!-- end online contact form -->



<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
