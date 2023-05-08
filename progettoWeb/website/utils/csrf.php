<?php

const SECRET = 'segretone';

function generate_token(): string
{
    return bin2hex(openssl_random_pseudo_bytes(16));
}

function check_fail(): void{
    header('location: /pages/security.php');
    die();
}

function check_csrf(): void
{
    // Double submit cookie
    // must be called on post requests
    if (!isset($_COOKIE['csrf_token']))
        check_fail();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']))
            check_fail();

        if ($_POST['csrf_token'] !== $_COOKIE['csrf_token'])
            check_fail();
    }

    /*
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (!isset($_GET['csrf_token']))
            check_fail();

        if ($_GET['csrf_token'] !== $_COOKIE['csrf_token'])
            check_fail();
    }
    */
}

function get_csrf_token_field(): string
{
    global $csrf_token;
    return '<input name="csrf_token" value="' . $csrf_token . '" type="hidden">';
}

global $csrf_token;
$csrf_token = generate_token();
setcookie('csrf_token', $csrf_token, 0, '/', '', false, true);