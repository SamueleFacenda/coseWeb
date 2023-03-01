<?php

// create jwt function
function createJwt($data, $secret) {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $data->creation_time = time();
    $payload = json_encode($data);
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    $sign = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
    $base64UrlSign = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($sign));

    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSign;
}

function verifyJwt($jwt, $secret, $maxAge = 3600) {
    $tks = explode('.', $jwt);
    if (count($tks) != 3) {
        return false;
    }
    list($headb64, $payloadb64, $cryptob64) = $tks;
    $header = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $headb64)));
    $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $payloadb64)));
    $sig = base64_decode(str_replace(['-', '_'], ['+', '/'], $cryptob64));
    $expectedSig = hash_hmac('sha256', $headb64 . "." . $payloadb64, $secret, true);

    if ($sig != $expectedSig) {
        return false;
    }
    $creation_time = $payload->creation_time;
    if (time() - $creation_time > $maxAge) {
        return false;
    }

    return $payload;
}

function getJwtDat($jwt){
    $tks = explode('.', $jwt);
    if (count($tks) != 3) {
        return false;
    }
    list($headb64, $payloadb64, $cryptob64) = $tks;
    $payload = json_decode(base64_decode($payloadb64));
    return $payload;
}

?>