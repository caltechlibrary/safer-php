<?php
/**
 * safer.php - a small input vetting library for PHP code.
 *
 * @author R. S. Doiel, <rsdoiel@caltech.edu>
 *
 * Copyright (c) 2017, Caltech
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

define("SAFER_VERSION", "v0.0.3");
if (defined("SAFER_ALLOW_UNSAFE") === false) {
    define("SAFER_ALLOW_UNSAFE", false);
}
if (defined("SAFER_ALLOWED_HTML") === false) {
    // List of safe elements derived from https://developer.mozilla.org/en-US/docs/Web/HTML/Element
    $safer_element_list = '<a><abbr><acronym><address><area><article><aside><audio><b><base><basefont><bdi><bdo><bgsound><big><blink><blockquote><body><br><button><canvas><caption><center><cite><code><col><colgroup><content><data><datalist><dd><decorator><del><details><dfn><dialog><dir><div><dl><dt><element><em><embed><fieldset><figcaption><figure><font><footer><form><frame><frameset><h1><h2><h3><h4><h5><h6><head><header><hgroup><hr><html><i><iframe><img><input><ins><isindex><kbd><keygen><label><legend><li><link><listing><main><map><mark><marquee><menu><menuitem><meta><meter><nav><nobr><noframes><noscript><object><ol><optgroup><option><output><p><param><picture><plaintext><pre><progress><q><rp><rt><ruby><s><samp><section><select><shadow><small><source><spacer><span><strike><strong><style><sub><summary><sup><table><tbody><td><template><textarea><tfoot><th><thead><time><title><tr><track><tt><u><ul><var><video><wbr><xmp>';
    define("SAFER_ALLOWED_HTML", $safer_element_list);
}

/**
 * Convert UTF-8 characters to appropriate HTML entities if mb_encode_numericentity() is defined.
 *
 * @param string $utf2html_string The string to convert.
 * @param bool $is_hex Whether to use hex encoding.
 * @return string The converted string.
 */
function utf2html(string $utf2html_string, bool $is_hex = false): string {
    if (!function_exists("mb_encode_numericentity")) {
        return $utf2html_string;
    }
    $f = 0xffff;
    $convmap = [
        160,  255, 0, $f,
        402,  402, 0, $f,  913,  929, 0, $f,  931,  937, 0, $f,
        945,  969, 0, $f,  977,  978, 0, $f,  982,  982, 0, $f,
        8226, 8226, 0, $f, 8230, 8230, 0, $f, 8242, 8243, 0, $f,
        8254, 8254, 0, $f, 8260, 8260, 0, $f, 8465, 8465, 0, $f,
        8472, 8472, 0, $f, 8476, 8476, 0, $f, 8482, 8482, 0, $f,
        8501, 8501, 0, $f, 8592, 8596, 0, $f, 8629, 8629, 0, $f,
        8656, 8660, 0, $f, 8704, 8704, 0, $f, 8706, 8707, 0, $f,
        8709, 8709, 0, $f, 8711, 8713, 0, $f, 8715, 8715, 0, $f,
        8719, 8719, 0, $f, 8721, 8722, 0, $f, 8727, 8727, 0, $f,
        8730, 8730, 0, $f, 8733, 8734, 0, $f, 8736, 8736, 0, $f,
        8743, 8747, 0, $f, 8756, 8756, 0, $f, 8764, 8764, 0, $f,
        8773, 8773, 0, $f, 8776, 8776, 0, $f, 8800, 8801, 0, $f,
        8804, 8805, 0, $f, 8834, 8836, 0, $f, 8838, 8839, 0, $f,
        8853, 8853, 0, $f, 8855, 8855, 0, $f, 8869, 8869, 0, $f,
        8901, 8901, 0, $f, 8968, 8971, 0, $f, 9001, 9002, 0, $f,
        9674, 9674, 0, $f, 9824, 9824, 0, $f, 9827, 9827, 0, $f,
        9829, 9830, 0, $f,
        338,  339, 0, $f,  352,  353, 0, $f,  376,  376, 0, $f,
        710,  710, 0, $f,  732,  732, 0, $f, 8194, 8195, 0, $f,
        8201, 8201, 0, $f, 8204, 8207, 0, $f, 8211, 8212, 0, $f,
        8216, 8218, 0, $f, 8218, 8218, 0, $f, 8220, 8222, 0, $f,
        8224, 8225, 0, $f, 8240, 8240, 0, $f, 8249, 8250, 0, $f,
        8364, 8364, 0, $f, $is_hex
    ];
    return mb_encode_numericentity($utf2html_string, $convmap, "UTF-8");
}

/**
 * Process a strtotime but THROW an exception if parse is bad.
 *
 * @param string $s String to parse.
 * @param int|false $offset A timestamp to parse relative to.
 * @return int A timestamp.
 * @throws Exception If parse fails.
 */
function saferStrToTime(string $s, int|false $offset = false): int {
    $time = $offset === false ? strtotime($s) : strtotime($s, $offset);
    if ($time === false || $time === -1) {
        throw new Exception("Can't parse date: $s");
    }
    return $time;
}

/**
 * Check if a string is a valid URL.
 *
 * @param string $s String to check.
 * @param string[]|null $protocols The list of accepted protocols.
 * @return bool True if a URL, false otherwise.
 */
function isValidUrl(string $s, ?array $protocols = null): bool {
    if (filter_var($s, FILTER_VALIDATE_URL) === false) {
        return false;
    }
    $protocols ??= ['http', 'https', 'ftp', 'sftp', 'mailto', 'tel'];
    $parts = parse_url($s);
    return isset($parts['scheme'], $parts['host']) && in_array($parts['scheme'], $protocols, true) && trim($parts['host']) !== "";
}

/**
 * Check if a string is a valid filename (alphanumeric plus .,_,-).
 *
 * @param string $s String to check.
 * @return bool True if valid filename, false otherwise.
 */
function isValidFilename(string $s): bool {
    return preg_match('/^(?:[a-z0-9_-]|\/|\.(?!\.))+$/iD', $s) === 1 && (!function_exists("mb_strlen") || mb_strlen($s, "UTF-8") < 250);
}

/**
 * Simple check for a probably valid email address.
 *
 * @param string $s The string to check.
 * @return bool True if valid email, false otherwise.
 */
function isValidEmail(string $s): bool {
    return filter_var($s, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Given an example $obj, calculate a viable validation map.
 *
 * @param array<string, mixed> $obj The input array (e.g., $_GET, $_POST, or $_SERVER).
 * @param bool $do_urldecode Flag to trigger urldecode of values before analysis.
 * @return array<string, string> A validation map array.
 */
function defaultValidationMap(array $obj, bool $do_urldecode = false): array {
    $is_varname = '/^([A-Z,a-z]|_|[0-9])+$/';
    $has_tags = '/(<[A-Z,a-z]+|<\/[A-Z,a-z]+>)/';
    $validation_map = [];

    foreach ($obj as $key => $value) {
        if (!isset($value)) {
            continue;
        }
        if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
            $validation_map[$key] = "Integer";
        } elseif (filter_var($value, FILTER_VALIDATE_FLOAT) !== false) {
            $validation_map[$key] = "Float";
        } elseif (filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null) {
            $validation_map[$key] = "Boolean";
        } elseif (is_string($value) && preg_match($is_varname, $value) === 1) {
            $validation_map[$key] = "Varname";
        } elseif (is_string($value) && preg_match($has_tags, $value) === 1) {
            $validation_map[$key] = "HTML";
        } elseif (isValidUrl($value)) {
            $validation_map[$key] = "Url";
        } elseif (isValidEmail($value)) {
            $validation_map[$key] = "Email";
        } else {
            $validation_map[$key] = "Text";
        }
    }
    return $validation_map;
}

/**
 * Remove attributes from HTML elements except for allowed ones.
 *
 * @param string $s The HTML string to be cleaned.
 * @param string[] $allowedattr An array of the allowed attributes (e.g., href, src, title, alt).
 * @return string HTML string with only allowed attributes.
 */
function strip_attributes(string $s, array $allowedattr = ["href", "src", "title", "alt"]): string {
    if (preg_match_all("/<[^>]*\\s([^>]*)\\/*>/msiU", $s, $res, PREG_SET_ORDER)) {
        foreach ($res as $r) {
            $tag = $r[0];
            $attrs = [];
            preg_match_all("/\\s.*=(['\"]).*\\1/msiU", " " . $r[1], $split, PREG_SET_ORDER);
            foreach ($split as $spl) {
                $attrs[] = $spl[0];
            }
            $newattrs = [];
            foreach ($attrs as $a) {
                $tmp = explode("=", $a, 2);
                if (count($tmp) === 2 && trim($tmp[0]) !== "" && in_array(strtolower(trim($tmp[0])), $allowedattr, true) && strpos(strtolower($tmp[1]), "javascript:") === false) {
                    $newattrs[] = trim($a);
                }
            }
            $attrs = implode(" ", $newattrs);
            $rpl = str_replace($r[1], $attrs, $tag);
            $s = str_replace($tag, $rpl, $s);
        }
    }
    return $s;
}

/**
 * Scan a string and convert quotes in the CDATA to appropriate entities.
 *
 * @param string $s The string to check and convert.
 * @return string String with quotes appropriately converted.
 */
function fix_html_quotes(string $s): string {
    $a = str_split($s);
    $inCData = true;
    foreach ($a as $i => $char) {
        if ($char === '>') {
            $inCData = true;
        } elseif ($char === '<') {
            $inCData = false;
        } elseif ($inCData) {
            $a[$i] = str_replace(['"', "'"], ['&quot;', '&apos;'], $char);
        }
    }
    return implode('', $a);
}

/**
 * Replace non-ASCII characters with hex code.
 *
 * @param string $value The string to escape.
 * @return string The escaped string.
 */
function escape(string $value): string {
    if (function_exists("mb_detect_encoding") && function_exists("mb_convert_encoding")) {
        $from_encoding = mb_detect_encoding($value);
        if ($from_encoding === false) {
            die("character encoding detection failed!");
        } elseif ($from_encoding !== "UTF-8") {
            $value = mb_convert_encoding($value, "UTF-8", $from_encoding);
        }
    }
    $search  = ["\\",   "\x00", "\n",  "\r",  "'",  '"',  "\x1a"];
    $replace = ["\\\\", "\\0",  "\\n", "\\r", "\'", '\"', "\\Z"];
    return str_replace($search, $replace, $value);
}

/**
 * Render a value using the specified format.
 *
 * @param mixed $value The value to be processed.
 * @param string $format The format to render (e.g., integer, float, varname, html, text, etc.).
 * @param bool $verbose Error log the result of makeAs for regular expression.
 * @return int|float|bool|string|array Returns the processed value, or false on failure.
 */
function makeAs(mixed $value, string $format, bool $verbose = false): int|float|bool|string|array {
    return match (strtolower($format)) {
        'array_text' => is_array($value) ? array_map(fn($val) => escape(strip_tags((string)$val)), $value) : false,
        'array_integers' => is_array($value) ? array_values(array_filter($value, fn($val) => is_numeric($val) && (string)(int)$val === (string)$val)) : false,
        'integer' => is_numeric($value) && (string)(int)$value === (string)$value ? (int)$value : false,
        'float' => is_numeric($value) && (string)(float)$value === (string)$value ? (float)$value : false,
        'boolean' => in_array($value, ['true', '1', true], true),
        'varname_dash' => is_string($value) ? preg_replace('/[^\w0-9_-]/', '', $value) : false,
        'varname' => is_string($value) ? preg_replace('/[^\w0-9_]/', '', $value) : false,
        'varname_list' => is_string($value) ? implode(',', array_map(fn($part) => preg_replace('/[^\w0-9_]/', '', $part), explode(',', $value))) : false,
        'html' => is_string($value) ? escape(fix_html_quotes(strip_attributes(strip_tags(utf2html($value), SAFER_ALLOWED_HTML)))) : false,
        'text' => is_string($value) ? escape(strip_tags($value)) : false,
        'url' => isValidUrl($value) ? $value : (strpos($value, '://') === false && isValidUrl('http://' . $value) ? 'http://' . $value : false),
        'email' => isValidEmail($value) ? $value : false,
        'filename' => isValidFilename($value) ? $value : false,
        default => preg_match(">" . '^' . str_replace(">", "\>", $format) . '$' . ">u", (string)$value) ? $value : false,
    };
}

/**
 * Generate a sanitized associative array from a raw input array.
 *
 * @param array<string, mixed> $raw_map The raw input array.
 * @param array<string, string>|null $validation_map Explicit validation map.
 * @param bool $verbose Log regexp makeAs results.
 * @param bool $enforceDefault If true, enforce a default value based on $format.
 * @return array<string, mixed> The sanitized version of $raw_map.
 */
function safer(array $raw_map, ?array $validation_map = null, bool $verbose = false, bool $enforceDefault = false): array {
    $results = [];
    if (SAFER_ALLOW_UNSAFE === true && $validation_map === null) {
        $validation_map = defaultValidationMap($raw_map, true);
    }
    if ($validation_map !== null) {
        $default_types = [
            'array_text' => [],
            'array_integers' => [],
            'integer' => 0,
            'float' => 0.0,
            'boolean' => false,
            'varname_dash' => '',
            'varname' => '',
            'varname_list' => [],
            'html' => '',
            'text' => '',
            'url' => '',
            'email' => '',
            'filename' => '',
        ];
        if ($enforceDefault) {
            foreach ($validation_map as $key => $format) {
                if (!isset($raw_map[$key])) {
                    $format = strtolower($format);
                    $raw_map[$key] = $default_types[$format] ?? '';
                }
            }
        }
        foreach ($validation_map as $key => $format) {
            $key = makeAs($key, "varname_dash", $verbose);
            if ($key !== false && isset($raw_map[$key])) {
                $results[$key] = makeAs($raw_map[$key], $format, $verbose);
            }
        }
    }
    return $results;
}

/**
 * Validate a JSON response against expected data types.
 *
 * @param string $json_string The JSON string to validate.
 * @param array<string, string> $validation_map The validation map.
 * @param bool $verbose Log regexp makeAs results.
 * @return array<string, mixed> The sanitized version of $json_string.
 */
function saferJSON(string $json_string, array $validation_map, bool $verbose = false): array {
    $obj = json_decode($json_string, true) ?: [];
    $results = [];
    foreach ($validation_map as $key => $format) {
        $key = makeAs($key, "varname", $verbose);
        if (is_string($key) && isset($obj[$key])) {
            $results[$key] = makeAs($obj[$key], $format, $verbose);
        }
    }
    return $results;
}

/**
 * Validate a string against being a safe filename.
 *
 * @param string $filename Name to be validated.
 * @param bool $verbose Log regexp makeAs results.
 * @return string|false The sanitized string or false.
 */
function saferFilename(string $filename, bool $verbose = false): string|false {
    return makeAs($filename, "filename", $verbose);
}
