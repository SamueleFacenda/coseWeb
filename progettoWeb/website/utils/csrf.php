<?php

const SECRET = 'segretone';

function generate_token(): string
{
    global $email;
    $checks = (object) array(
        'page'=>$_SERVER['PHP_SELF'],
        'email'=>$email
    );

    return createJwt($checks, SECRET);
}

function check_fail(): void{
    header('location: /pages/security.php');
    die();
}

function check_token($token): void
{
    global $email;
    // token expire in 5d
    if(!verifyJwt($token, SECRET, 60 * 60 * 24 * 5))
        check_fail();

    $data = getJwtDat($token);

    if($data->email !== $email)
        check_fail();


    if($data->page !==$_SERVER['PHP_SELF'])
        check_fail();
}

global $csrf_token;
$csrf_token = generate_token();
