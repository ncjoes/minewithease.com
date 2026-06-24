<?php
declare(strict_types=1);

/**
 * Generate encryption for email verification
 *
 * @param type $id
 * @param type $hash
 *
 * @return string
 */
function encrypt_for_verification($id, $hash, $redirect = null)
{
    if (empty($redirect)) {
        $redirect = url('/');
    }

    return encrypt("$id $hash $redirect");
}

/**
 * Decrypt encryption for email verification
 *
 * @param type $encryption
 *
 * @return array
 */
function decrypt_for_verification($encryption)
{
    $d = explode(' ', decrypt($encryption));
    if (isset($d[0]) && isset($d[1])) {
        $result['id'] = $d[0];
        $result['hash'] = $d[1];
        if (isset($d[2])) {
            $result['rdr'] = $d[2];
        }

        return $result;
    } else {
        throw new Exception('String could not be decrypted');
    }
}

/**
 * @param int $length
 * @return string
 */
function random_chars($length = 8)
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $result = '';
    $str_len = strlen((string) $chars);
    for ($i = 1; $i <= $length; $i++) {
        $pos = rand(0, $str_len - 1);
        $result .= substr($chars, $pos, 1);
    }

    return $result;
}

/**
 * @param $html
 * @return string|string[]|null
 */
function remove_scripts($html)
{
    return preg_replace('#<\\s*/*\\s*script\\s*>#', '', $html);
}

/**
 * @param $password
 * @param $hash
 * @return bool
 */
function verify_password($password, $hash)
{
    return crypt($password, $hash) === $hash;
}
