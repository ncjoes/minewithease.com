<?php
declare(strict_types=1);

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @param mixed $find
 * @param mixed $replacements
 * @param mixed $subject
 *
 * @return mixed
 */
function str_replace_recursive($find, $replacements, $subject)
{
    $num_replacements = 0;
    $subject          = str_replace($find, $replacements, $subject, $num_replacements);
    if ($num_replacements == 0)
        return $subject;
    else
        return str_replace_recursive($find, $replacements, $subject);
}

/**
 * @param $mixed
 *
 * @return bool
 */
function is_json($mixed)
{
    return (is_string($mixed) and json_decode($mixed) and json_last_error() == JSON_ERROR_NONE);
}

/**
 * @param $name
 * @param bool $numbers_allowed
 * @return false|int
 */
function is_name($name, bool $numbers_allowed = false)
{
    if ($numbers_allowed)
        return preg_match("/^[a-zA-Z0-9 ]*$/", $name);
    else
        return preg_match("/^[a-zA-Z ]*$/", $name);
}

/**
 * @param $email
 * @return string
 */
function normalize_email($email): string
{
    $parts = explode('@', strtolower($email));
    switch ($parts['1']) {
        case 'gmail.com' :
            {
                $parts[0] = str_replace('.', '', $parts[0]);
            }
            break;
        default:
        {
            $parts[0] = str_replace('.', '', $parts[0]);
        }
    }

    return implode('@', $parts);
}

/**
 * @param $url
 * @return mixed
 */
function normalize_url($url)
{
    return str_replace(DS, '/', $url);
}

/**
 * @param $path
 * @return array|string|string[]
 */
function normalize_path($path)
{
    return str_replace('/', DS, $path);
}

/**
 * Fancifully converts numbers to a more human friendly format. e.g 1000 = 1K
 * and 1000000 = 1M
 *
 * @param float $number
 *
 * @return string
 */
function fancy_count(float $number)
{
    $sizes = ['K', 'M', 'B', 'Z'];
    if ($number < 1000) {
        return $number;
    }
    $i = intval(floor(log($number) / log(1000)));

    return round($number / pow(1000, $i), 2) . $sizes[$i - 1];
}

/**
 * Allows count only to max value
 *
 * @param number $number
 * @param int $max
 *
 * @return string
 */
function fancy_max_count($number, int $max = 99): string
{
    return $number > 99 ? "$max+" : "$number";
}

/**
 * @param $number
 * @param $symbol
 * @param int $dp
 * @return string
 */
function to_currency($number, $symbol, int $dp = 2): string
{
    $dp = $number == 0 ? 2 : $dp;
    $number = number_format((float)$number, $dp);
    return $symbol == '$' ? ($symbol . $number) : $number . ' ' . $symbol;
}

/**
 * @param $number
 * @param int $p
 * @return float|int
 */
function percentage($number, int $p = 100)
{
    return ($number * $p / 100);
}

/**
 * @param $haystack
 * @param $needle
 * @return int
 */
function search_with_weight($haystack, $needle): int
{
    if (Str::startsWith($haystack, $needle)) {
        return 3;
    } elseif (stripos($haystack, $needle) !== false) {
        return 2;
    } else {
        $pattern = preg_replace('/\\s+/', '|', $needle);

        return preg_match("/{$pattern}/i", $haystack) ? 1 : 0;
    }
}

/**
 * @param $string
 * @param int $maxChar
 * @return string
 */
function clamp($string, int $maxChar = 8): string
{
    if (strlen((string)$string) > $maxChar) {
        return substr($string, 0, $maxChar) . '...';
    }

    return $string;
}

/**
 * @param $length
 * @return string
 */
function random_number($length): string
{
    $numbers = range(0, 9);
    shuffle($numbers);

    return implode('', Arr::random($numbers, $length));
}


/**
 * @param $url
 *
 * @return bool|mixed
 */
function get_domain($url)
{
    $host = @parse_url($url, PHP_URL_HOST);
    // If the URL can't be parsed, use the original URL
    // Change to "return false" if you don't want that
    if (!$host) $host = $url;
    // The "www." prefix isn't really needed if you're just using
    // this to display the domain to the user
    if (substr($host, 0, 4) == "www.") $host = substr($host, 4);
    // You might also want to limit the length if screen space is limited
    if (strlen($host) > 50) $host = substr($host, 0, 47) . '...';

    return $host;
}
