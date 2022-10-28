<?php

/**
 * my-image-cdn (https://github.com/laelbe/my-image-cdn)
 * configuration file.
 */

if (!defined('_MYCDN_')) {
    exit();
}

// origin hostname
$original_host = 'https://blog.lael.be';

// mycdn agentname
$mycdn_agentname = 'MYCDN/1.0';

// allowed extensions. please write in lowercase.
$allowed_extension = [];
$allowed_extension[] = 'png';
$allowed_extension[] = 'jpg';
$allowed_extension[] = 'jpeg';
$allowed_extension[] = 'gif';
$allowed_extension[] = 'css';
$allowed_extension[] = 'js';

// lowercase headername + colon(:) + custom value
// SEE : https://developer.mozilla.org/ko/docs/Web/HTTP/Headers
$custom_headers = [];
// $custom_headers[] = 'authorization: Bearer MySampleToken';
// $custom_headers[] = 'authorization: Basic YWxhZGRpbjpvcGVuc2VzYW1l';
// $custom_headers[] = 'x-mycdn-worker: ' . gethostname();
// $custom_headers[] = 'x-api-key: my_custom_key';
