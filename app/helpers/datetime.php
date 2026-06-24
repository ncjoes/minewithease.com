<?php
declare(strict_types=1);

use Carbon\Carbon;

/**
 * @param $time
 * @param string $prefix
 * @return string
 */
function diff_for_humans($time, $prefix = 'About ')
{
    if (!$time instanceof Carbon) {
        if (is_int($time)) {
            $time = Carbon::createFromTimestamp($time);
        } else {
            $time = Carbon::createFromTimestamp(strtotime($time));
        }
    }

    return $prefix.$time->diffForHumans();
}

/**
 * @param $time
 * @param string $format
 * @return string
 */
function date_time_for_humans($time, $format = DATETIME_FORMAT)
{
    if (!$time instanceof Carbon) {
        if (is_int($time)) {
            $time = Carbon::createFromTimestamp($time);
        } elseif (is_string($time)) {
            $time = Carbon::createFromTimestamp(strtotime($time));
        } else {
            return is_null($time) ? 'nil' : $time;
        }
    }

    return $time->format($format);
}

/**
 * @param $time
 * @return string
 */
function date_for_humans($time)
{
    if (!$time instanceof Carbon) {
        if (is_int($time)) {
            $time = Carbon::createFromTimestamp($time);
        } elseif (is_string($time)) {
            $time = Carbon::createFromTimestamp(strtotime($time));
        } else {
            return is_null($time) ? 'nil' : $time;
        }
    }

    return $time->format('M d, Y');
}

function time_for_humans($time, $seconds = true)
{
    if (!$time instanceof Carbon) {
        if (is_int($time)) {
            $time = Carbon::createFromTimestamp($time);
        } elseif (is_string($time)) {
            $time = Carbon::createFromTimestamp(strtotime($time));
        } else {
            return is_null($time) ? 'nil' : $time;
        }
    }

    return $time->format($seconds ? 'H:i:s' : 'H:i');
}

if (!function_exists('month_names')) {
    /**
     * @return array
     */
    function month_names()
    {
        return [
            '01' => "January",
            '02' => "February",
            '03' => "March",
            '04' => "April",
            '05' => "May",
            '06' => "June",
            '07' => "July",
            '08' => "August",
            '09' => "September",
            '10' => "October",
            '11' => "November",
            '12' => "December",
        ];
    }
}
