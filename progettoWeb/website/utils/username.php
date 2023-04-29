<?php
// define jwt lifetime of one month
$maxAge = 3600 * 24 * 30;

// secret get env
$secret = getenv('JWT_SECRET');
// default secret
if ($secret == null){
    $secret = 'segretissimo!!';
}


global $username;
$username = null;
// set username if jwt is set
if(isset($_COOKIE['jwt']) && verifyJwt($_COOKIE['jwt'], $secret, $maxAge)){
    $jwt = $_COOKIE['jwt'];
    $payload = getJwtDat($jwt);
    $username = $payload->username;
    $email = $payload->email;

    $creationTime = $payload->creation_time;
    if(time() - $creationTime > 3600*24){
        // refresh jwt
        $jwt = createJwt((object) ['username' => $username, 'email' => $email, 'admin' => $payload->admin], $secret);
        // set jwt cookie for one month
        setcookie('jwt', $jwt, time() + 3600 * 24 * 30 , '/', httponly: true);
    }

    include_once '/utils/csrf.php';
}