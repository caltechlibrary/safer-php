<?php
/**
 * saferclient.php contains PHP code to test a remote web service or application.
 * Currently is provides a simple wrapper to PHP's CURL package based on David
 * from Code2Design.com hosted at php.net.
 */

/**
 * saferHttpGet() - performs an HTTP GET operation using the PHP Curl package.
 * @param $base_url - the URL to "get" (without parameters)
 * @param $params - key/value pairs to combine with the URL (e.g. what you see to the right of the question mark
 * in https://example.org/?q=Some%20Search%20text). $params values should not be URL encoded yet.
 * @return an associative array contains "content" or "error" if there was an error
 *
 * This was based on David from Code2Design.com example at php.net
 */
function saferHttpGet($base_url, array $get = array()) 
{
    $options = array(
        CURLOPT_URL => $base_url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_TIMEOUT => 10
    );
    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $results = array(
        "content" => "",
        "error" => ""
    );
    if ( ! $content = curl_exec($ch))
    {
        $results["error"] = curl_error($ch);
    } else {
        $results["content"] = $content;
    }
    curl_close($ch);
    return $results;
    
}

/**
 * saferHttpPost() - performs an HTTP POST operation using the PHP Curl package
 * @param $base_url - the URL to "post" to (without parameters)
 * @param $params - the key/value pairs (not URL encoded) you wish to "post"
 * @return an associative array containing "content" or "error" if there was an error.
 *
 * This was based on David from Code2Design.com example at php.net
 */
function saferHttpPost($base_url, $post = [])
{
    $url = $base_url;
    $options = array(
        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($post),
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_TIMEOUT => 10
    );

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $results = array(
        "content" => "",
        "error" => ""
    );
    if ( ! $content = curl_exec($ch))
    {
        $results["error"] = curl_error($ch);
    } else {
        $results["content"] = $content;
    }
    curl_close($ch);
    return $results;
}
