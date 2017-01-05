<?php 
/********************************************************************************
 * This is an example of an old feedback form retrofitted with safer.php
 * Previously this file had been modified last on Feb 4, 2011
 ********************************************************************************/
require('../safer.php');
// Form uses $_SERVER, $_SESSION and $_POST, setup safer copy of $_POST.
$_server = array();
$_post = array();
if (isset($_POST)) {
    $_post = safer($_POST, array(
        "submitted" => "text",
        "name" => "text",
        "email" => "email",
        "validator" => "text",
        "affiliation" => "text",
        "title" => "text",
        "author" => "text",
        "isbn" => "text",
        "url" => "url",
        "ebook" => "text",
        "copy" => "text",
        "comments" => "text",
    ));
    if (isset($_post["comments"])) {
        $_post["comments"] = str_replace("\\r\\n", "\n\r", $_post["comments"]);
    }
}
if (isset($_SERVER)) {
    $_server = safer($_SERVER, array(
        "DOCUMENT_ROOT" => "text",
        "PHP_SELF" => "text",
        "REMOTE_ADDR" => "text",
        "HTTP_USER_AGENT" => "text",
    )); 
}

// In this rest of this file rename $_POST to safer $_post
// and $_SERVER to safer $_server
/** REST of form is the same as before **/
?>
<?php 
/*header("Expires: Thu, 17 May 2001 10:17:17 GMT");
header("Last-Modified: " .gmdate("D,d M Y H:i:s") . "GMT"));*/
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");
error_reporting(E_ALL & ~E_NOTICE | E_STRICT);
?>

<?php
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
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/general_page_php.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Suggest a Book | Caltech Library</title>
<!-- InstanceEndEditable -->
<?php $baseURL = $_server['DOCUMENT_ROOT']."/common/" ?>
<?php include_once $baseURL."scripts.html" ?>
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
<?php include_once $baseURL."pre_con.php"?>
<!-- InstanceBeginEditable name="body content" -->
<h3>Suggest a book for purchase </h3>
<p>If there’s a book that the library doesn’t own that would be useful to you for your research or coursework, please let us know.  For more details on what we collect, please see our <a href="http://library.caltech.edu/about/Collection_Development_Policy.htm">Collection Development Policy</a>.</p>
<table border="0" cellspacing="0">
  <tr> 
    <td>
	<!-- online feedback form -->
	<?php session_start(); ?>
	<?php if ( $_post['submitted'] == 'true' && empty($_post['name']) ): ?>
	<p><font color="#FF0000">Missing Your Name</font></p>    <?php endif; ?>
    <?php if ( $_post['submitted'] == 'true' && empty($_post['email']) ): ?>
    <p><font color="#FF0000">Missing Your E-Mail Address</font></p>   <?php endif; ?>
	
    <?php if ( $_post['submitted'] == 'true' && empty($_post['validator']) ): ?>
    <p><font color="#FF0000">Missing security code</font></p>   <?php endif; ?>
    
    <?php if ($_post['submitted'] == 'true' && $_post['affiliation'] == 'Choose an affiliation...'): ?>
    <p><font color="#FF0000">Missing Your Caltech Affiliation</font></p> <?php endif; ?>
    
    <?php if ( $_post['submitted'] == 'true' && $_post['email'] <> '' && (checkEmail($_post['email']) == FALSE)): ?> 
	<p><font color="#FF0000">E-mail entered is not valid.</font></p>  <?php endif; ?>

    <?php if ($_post['submitted']== 'true' && $_post['validator'] <> '' && $_post['validator'] <> $_SESSION['IMAGE_CODE']): ?>
    <p><font color="#FF0000">Invalid security code.</font></p> 
	
	<?php // for testing 
	//echo "validator = ", $_post['validator'], "\n"; 
	//echo " IMAGE_CODE = ", $_SESSION['IMAGE_CODE'];
	?>
    <?php endif; ?>

    <?php if ( $_post['submitted'] <> 'true' || empty($_post['name']) || empty($_post['email']) || 
	checkEmail($_post['email'])== FALSE || empty($_post['validator']) || $_post['validator'] <> $_SESSION['IMAGE_CODE'] || $_post['affiliation'] == 'Choose an affiliation...'): ?>
   
 <!-- <p> (<span class="redasterisk"> *</span>) Denotes a Required Field.<p/> -->
  <form action="<?php echo $_server['PHP_SELF']; ?>" method="post" id="form_fields">
 <!-- <p> Your Name (<span class="redasterisk"> *</span>):<br /> -->
  <p> Your Name <label><small>(required)</small></label>:<br />
      <input name="name" size="45" value="<?php echo $_post['name']; ?>" /> 
  </p>
  <!--<p>Your E-Mail Address (<span class="redasterisk"> *</span>):<br /> -->
  <p> Your E-Mail Address <label><small>(required)</small></label>:<br />
  <small>Please use your Caltech email address, if available</small><br />
      <input name="email" size="45" value="<?php echo $_post['email']; ?>" />
  </p>
  <p>Your Caltech Affiliation: <label><small>(required)</small></label>:<br />
      <select name="affiliation">
      <option <?php if (empty($_post['affiliation'])) echo "SELECTED"; ?> >Choose an affiliation...</option>
      <option <?php if ($_post['affiliation'] ==  'Faculty') echo "SELECTED"; ?> >Faculty</option>
      <option <?php if ($_post['affiliation'] ==  'Student') echo "SELECTED"; ?> >Student</option>
      <option <?php if ($_post['affiliation'] ==  'Staff') echo "SELECTED"; ?> >Staff</option>
      <option <?php if ($_post['affiliation'] ==  'Postdoc') echo "SELECTED"; ?> >Postdoc</option>
      <option <?php if ($_post['affiliation'] ==  'Other (Please explain in Comments field)') echo "SELECTED"; ?> >Other (Please explain in Comments field)</option>
      <option <?php if ($_post['affiliation'] ==  'None') echo "SELECTED"; ?> >None</option>
      </select>
  <p> Book Title:<br />
	<input name="title" size="45" value="<?php echo $_post['title']; ?>" />
</p>
  <p> Author:<br />
	<input name="author" size="45" value="<?php echo $_post['author']; ?>" />
</p>
  <p> ISBN:<br />
	<input name="isbn" size="45" value="<?php echo $_post['isbn']; ?>" />
</p>
  <p>URL:<br />
	<input name="url" size="45" value="<?php echo $_post['url']; ?>" />
</p>
<p>
<input name="ebook" type="checkbox" value="ebook" <?php if ($_post['ebook']=="ebook") {echo "checked" ;} ?> /> I'd prefer an ebook, if possible
</p>
  <p>
    <input name="copy" type="checkbox" value="copy" <?php if ( $_post['copy']== "copy" ) { echo "checked" ;} ?> /> Send me a copy of this message
  </p>
<p>Please enter these characters 
  <label><small>(required)</small></label>:<br />
<input type="text" name="validator" id="validator" size="20" />
<img src="random.php" alt="secure code" width="80" height="20" vspace="1" align="top" />
</p>


  <p>Comments:<br />
      <textarea name="comments" rows="10" cols="40"><?php echo $_post['comments']; ?></textarea>
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
if ( $_post['submitted'] == 'true' && !empty($_post['validator']) && $_post['validator'] == $_SESSION['IMAGE_CODE']
	&& $_post['name'] <> '' && $_post['email'] <> '' && checkEmail($_post['email'])<> FALSE && $_post['affiliation'] <> 'Choose an affiliation...')
{
   define (NL, "<BR />\n");
	echo ("<h4>Your feedback has been submitted.</h4>\n");

    unset($_SESSION['IMAGE_CODE']); //**************************************
    session_destroy();
   	$timestamp = date("F dS, Y h:i:s a");
	
	if (substr($_post['url'], 0, 7)=='http://') $url_link = "<a href=".$_post['url'].">".substr($_post['url'], 0, 60)."</a>";
	else $url_link = substr($_post['url'], 0, 60);
	
	echo ("<P>\n");
	echo ("Time: " . $timestamp . NL);
	echo ("Name: " . $_post['name'] . NL);
	echo ("Email: " . $_post['email'] . NL);
	echo ("Caltech Affiliation: " . $_post['affiliation'] . NL);
//	echo ("URL: <a href=\"".$_post['url']."\">" . substr($_post['url'], 0, 60) . "</a>".NL);
	echo ("Title: " .$_post['title'] . NL);
	echo ("Author: " .$_post['author'] . NL);
	echo ("ISBN: " .$_post['isbn'] . NL);
	echo ("URL: " . $url_link . NL);
	if ($_post['ebook']=="ebook") 
		{echo ("ebook: yes" . NL);}
	else
		 {echo ("ebook: no" . NL);}
	echo ("Comments: <br><pre>" . $_post['comments'] . NL);
	echo ("</pre></p><p>");
	echo ("<a href="/"> back to Caltech Library</a>");
    echo ("</P></blockquote>");

	$output = "Time: " . $timestamp . "\n";
	$output .= "IP: " . $_server['REMOTE_ADDR'] . "\n";
	$output .= "User Agent: " . $_server['HTTP_USER_AGENT'] . "\n";
	$output .= "Name: " . $_post['name'] . "\n";
	$output .= "Email: " . $_post['email']. "\n";
	$output .= "Caltech affiliation: " . $_post['affiliation'] . "\n";
	$output .= "Title: " .$_post['title']. "\n";
	$output .= "Author: " .$_post['author']. "\n";
	$output .= "ISBN: " .$_post['isbn']. "\n";
	$output .= "URL: " . $_post['url']. "\n";
	if ($_post['ebook']=="ebook")
		$output .= "ebook: yes\n";
	else
		$output .= "ebook: no\n";
	$output .= "Comments: " . "\n". $_post['comments']. "\n";
	$bcc = "web@library.caltech.edu,";
	if ( $_post['copy']== "copy" ) { $bcc .= $_post['email']; }
// NOTE: Commented out for demo purposes -- mail("library@caltech.libanswers.com ", "Caltech Library Services Purchase Request", $output, "From: ".$_post['email']."\r\n"."Bcc: ".$bcc."\r\n");

}
?>
<!-- end online contact form -->
</td>
  </tr>
</table>



<!-- InstanceEndEditable -->
<?php include_once $baseURL."post_con.php"?>
<?php include_once $baseURL."footer.html"?>
</body>
<!-- InstanceEnd --></html>
