<?php

/**
 * Credits: http://php.net/manual/en/function.filesize.php
 *
 * @param $bytes
 * @param $decimals
 *
 * @return bool|int
 */
function bytes_to_size($bytes, $decimals = 2)
{
    $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    $factor = floor((strlen((string) $bytes) - 1) / 3);

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)).$sizes[$factor];
}

/**
 * Credits: http://php.net/manual/en/function.filesize.php
 *
 * @param $url
 *
 * @return bool|int
 */
function bytes_to_size_remote($url)
{
    static $regex = '/^Content-Length: *+\K\d++$/im';
    if (!$f_open = @fopen($url, 'rb')) {
        return false;
    }
    if (
        isset($http_response_header) &&
        preg_match($regex, implode("\n", $http_response_header), $matches)
    ) {
        return (int) $matches[0];
    }

    return strlen(stream_get_contents($f_open));
}
