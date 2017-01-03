<?php 
/*header("Expires: Thu, 17 May 2001 10:17:17 GMT");
header("Last-Modified: " .gmdate("D,d M Y H:i:s") . "GMT"));*/
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");

function checkEmail($email) 
{
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
}

require('../safer-php/safer.php');
$server = safer($_SERVER);
$session = safer($_SESSION);
$post = safer($_POST);

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/general_page_php.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Mobile Feedback</title>
<!-- InstanceEndEditable -->
<?php $baseURL = $server['DOCUMENT_ROOT']."/common/" ?>
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
DEBUG: <pre>
<?php
echo json_encode($server) . " -> " . json_encode($_SERVER) . PHP_EOL;
echo json_encode($session) . " -> " . json_encode($_SESSION) . PHP_EOL;
echo json_encode($post) . " -> " . json_encode($_POST) . PHP_EOL;
?>
</pre>
<!-- InstanceBeginEditable name="body content" -->
<h3>Have Feedback? </h3>

	<!-- online feedback form -->
	<?php session_start(); ?>
	<?php if ( $post['submitted'] == 'true' && empty($post['name']) ): ?>
	<p><font color="#FF0000">Missing Your Name</font></p>    <?php endif; ?>
    <?php if ( $post['submitted'] == 'true' && empty($post['email']) ): ?>
    <p><font color="#FF0000">Missing Your E-Mail Address</font></p>   <?php endif; ?>
	
    <?php if ( $post['submitted'] == 'true' && empty($post['validator']) ): ?>
    <p><font color="#FF0000">Missing security code</font></p>   <?php endif; ?>
    <?php if ( $post['submitted'] == 'true' && $post['email'] <> '' && (checkEmail($post['email']) == FALSE)): ?> 
	<p><font color="#FF0000">E-mail entered is not valid.</font></p>  <?php endif; ?>

    <?php if ($post['submitted']== 'true' && $post['validator'] <> '' && $post['validator'] <> $session['IMAGE_CODE']): ?>
    <p><font color="#FF0000">Invalid security code.</font></p> 
	
	<?php // for testing 
	//echo "validator = ", $post['validator'], "\n"; 
	//echo " IMAGE_CODE = ", $session['IMAGE_CODE'];
	?>
    <?php endif; ?>

    <?php if ( $post['submitted'] <> 'true' || empty($post['name']) || empty($post['email']) || 
	checkEmail($post['email'])== FALSE || empty($post['validator']) || $post['validator'] <> $session['IMAGE_CODE']): ?>
   
 <!-- <p> (<span class="redasterisk"> *</span>) Denotes a Required Field.<p/> -->
  <form action="<?php echo $server['PHP_SELF']; ?>" method="post" id="form_fields">
 <!-- <p> Your Name (<span class="redasterisk"> *</span>):<br /> -->
  <p> Your Name <label><small>(required)</small></label>:<br />
      <input name="name" size="45" value="<?php echo $post['name']; ?>" /> 
  </p>
  <!--<p>Your E-Mail Address (<span class="redasterisk"> *</span>):<br /> -->
  <p> Your E-Mail Address <label><small>(required)</small></label>:<br />
      <input name="email" size="45" value="<?php echo $post['email']; ?>" />
  </p>
  <p>
    <input name="copy" type="checkbox" value="copy" <?php if ( $post['copy']== "copy" ) { echo "checked" ;} ?> /> 
    Send me a copy of this email
  </p>
<p>Please enter these characters 
  <label><small>(required)</small></label>:<br />
<input type="text" name="validator" id="validator" size="20" />
<img src="/about/random.php" alt="secure code" width="80" height="20" vspace="1" align="top" />
</p>


  <p>Comments:<br />
      <textarea name="comments" rows="10" cols="40"><?php echo $post['comments']; ?></textarea>
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
if ( $post['submitted'] === 'true' && !empty($post['validator']) && $post['validator'] === $session['IMAGE_CODE'] && 
     $post['name'] !== '' && $post['email'] !== '' && checkEmail($post['email']))
{
   define (NL, "<BR />\n");
	echo ("<h4>Your feedback has been submitted.</h4>\n");

    unset($session['IMAGE_CODE']); //**************************************
    session_destroy();
   	$timestamp = date("F dS, Y h:i:s a");
	
	echo ("<P>\n");
	echo ("Time: " . $timestamp . NL);
	echo ("Name: " . $post['name'] . NL);
	echo ("Email: " . $post['email'] . NL);
	echo ("Comments: <br><pre>" . $post['comments'] . NL);
	echo ("</pre></p><p>");
	echo ('<a href="/m"> Mobile home</a>');
        echo ("</P></blockquote>");

	$output = "Time: " . $timestamp . "\n";
	$output .= "IP: " . $server['REMOTE_ADDR'] . "\n";
	$output .= "User Agent: " . $server['HTTP_USER_AGENT'] . "\n";
	$output .= "Name: " . $post['name'] . "\n";
	$output .= "Email: " . $post['email']. "\n";
	$output .= "URL: " . $post['url']. "\n";
	$output .= "Comments: " . "\n". $post['comments']. "\n";
	$bcc = "rsdoiel@caltech.edu,";
	if ( $post['copy']== "copy" ) { $bcc .= $post['email']; }

//mail("library@caltech.edu ", "Caltech Library Services Mobile Feedback", $output, "From: ".$post['email']."\r\n"."Bcc: ".$bcc."\r\n");
print('<pre>' . $output . '</pre>');

}
?>
<!-- end online contact form -->



<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
