<?php

use Illuminate\Http\JsonResponse;

/**
 * @return string
 */
function redirect_on_auth_success()
{
    return redirect()->intended()->getTargetUrl();
}

/**
 * @param $data
 *
 * @return JsonResponse
 */
function to_json($data)
{
    if (is_json($data))
        return $data;

    return response()->json($data);
}

/**
 * @param string $view
 * @param array $data
 * @param boolean $cache
 *
 * @return mixed
 */
function i_response($view, $data = [], $cache = false)
{
    if (request()->wantsJson()) {
        $response = response()->json($data);
    } else {
        $response = response()->view($view, $data);
    }
    if ($cache) {
        return $response->header('cache-control', 'public');
    } else {
        return $response;
    }
}

/**
 * More intelligent interface to system calls
 *
 * @link http://php.net/manual/en/function.system.php
 *
 * @param        $cmd
 * @param string $input
 *
 * @return array
 */
function i_exec($cmd, $input = '')
{
    $process = proc_open($cmd, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    fwrite($pipes[0], $input);
    fclose($pipes[0]);
    $stdout = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    $rtn = proc_close($process);

    return [
        'stdout' => $stdout,
        'stderr' => $stderr,
        'return' => $rtn,
    ];
}

/**
 * @return bool
 */
function usingSocialite()
{
    return request()->session()->has('SocialitePack');
}

function get_anchor($model, $title = null, $highlight = true)
{
    if (empty($title)) {
        try {
            $title = $model->name;
        }
        catch (Exception $e) {
        }
    }

    $anchor = '<a href="'.$model->url.'"';
    if ($highlight) {
        $anchor .= ' class="font-bold"';
    }
    $anchor .= '>'.$title.'</a>';

    return $anchor;
}

/**
 * @param $message
 */
function println($message)
{
    echo $message."\n\r";
}

/**
 * @param $url
 * @param array $headers
 * @param int $connection_timeout
 * @param int $run_time
 * @return array
 */
function my_curl($url, $headers = [], $connection_timeout = 5, $run_time = 30)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
    /**
     * true to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
     */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connection_timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $run_time);
    if (sizeof($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $data = curl_exec($ch);

    $info = curl_getinfo($ch);
    $curl_error = curl_errno($ch) ? curl_error($ch) : '';
    curl_close($ch);

    $http_status_code = $info['http_code'];

    return ($http_status_code >= 200 && $http_status_code < 300)
        ? ['status' => true, 'curl_info' => $info, 'data' => $data] : ['status' => false, 'curl_info' => $info, 'curl_error' => $curl_error];
}
