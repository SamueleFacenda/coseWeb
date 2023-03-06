<?php
include_once '../utils/jwt.php';
$secret = getenv('JWT_SECRET');
if(empty($secret)){
    $secret = 'segretissimo!!';
}

$verified = false;
$invalid_jwt = false;
$to_verify = false;
$sent = false;


if(isset($_GET['verify'])){
    $jwt = $_GET['jwt'];
    if(!verifyJwt($jwt, $secret)){
        $invalid_jwt = true;
    }else{
        $payload = getJwtDat($jwt);
        $email = $payload->email;
        $verify = $payload->verify;
        if($verify === 'verify' && time() - $payload->time < 3600*24){
            connect();
            verify_email($email);
            $verified = true;
        }else{
            $invalid_jwt = true;
        }
    }
}

if(isset($_GET['email'])){
    $to_verify = true;
    $email = $_GET['email'];
}

if(isset($_GET['sendemail'])){
    // TODO
    $email = $_GET['sendemail'];
    $jwt = createJwt((object) ['email' => $email, 'verify' => 'verify', 'time' => time()], $secret);
    $url = 'http://facenda5inc2022/pages/email.php?verify=true&jwt=' . urlencode($jwt);
    $subject = 'Verify your email';
    $message = 'Click on the link to verify your email: ' . $url;
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    mail($email, $subject, $message, $headers);
    $sent = true;
}


?>


<!DOCTYPE html>
<html lang="en">
<?php
// import head
include_once '../static/head.php';
echo '<body>';
include_once '../utils/username.php';
include_once '../static/navbar.php';
?>
<section class="text-center">
    <?php if($verified): ?>
        <h1 class="display-4">Email verified</h1>
        <p class="lead">You can now login</p>
    <?php elseif($invalid_jwt): ?>
        <h1 class="display-4">Invalid token</h1>
        <p class="lead">The token is invalid or has expired</p>
    <?php elseif($to_verify): ?>
        <h1 class="display-4">Pending verification</h1>
        <p class="lead">You should have recived a verification email.</p>
        <p class="lead">If not, or the verification code has expired(1d), click <a href="email.php?sendemail=<?=$email?>">here</a> to resend the email</p>
    <?php elseif($sent): ?>
        <h1 class="display-4">Email sent</h1>
        <p class="lead">Check you email and also the spam folder</p>
        <p class="lead">If you cannot find it, or the verification code has expired(1d), click <a href="email.php?sendemail=<?=$email?>">here</a> to resend the email</p>
        <p class="lead">Email sent to <?=$email?></p>
    <?php endif; ?>
</section>
<?php
include_once '../static/footer.php';
?>
</body>
</html>