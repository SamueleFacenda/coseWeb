<?php
include_once '../utils/jwt.php';
include_once '../utils/connection.php';
$secret = getenv('JWT_SECRET');
if(empty($secret)){
    $secret = 'segretissimo!!';
}

$verified = false;
$invalid_jwt = false;
$to_verify = false;
$sent = false;


if(isset($_GET['verify'])){
    $jwt = $_GET['verify'];
    if(!verifyJwt($jwt, $secret)){
        $invalid_jwt = true;
    }else{
        $payload = getJwtDat($jwt);
        $email = $payload->email;
        $verify = $payload->verify;
        if($verify === 'verify' && time() - $payload->time < 3600*24){
            connect();
            set_email_verified($email);
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
    $email = $_GET['sendemail'];
    $jwt = createJwt((object) ['email' => $email, 'verify' => 'verify', 'time' => time()], $secret);
    $url = 'http://facenda5inc2022.altervista.org/pages/email.php?verify=' . urlencode($jwt);
    $subject = 'Verify your email';
    $message = 'Click on the link to verify your email: ' . $url;
    $headers = 'From: "Samuele Facenda" <facenda5inc2022@altervista.org>' . "\r\n";
    //$headers .= "MIME-Version: 1.0" . "\r\n";
    //$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $res = mail($email, $subject, $message, $headers);
    $sent = true;
}

?>


<!DOCTYPE html>
<html lang="en">
<?php
// import head
include_once '../static/head.php';
echo '<body class="d-flex flex-column min-vh-100">';
include_once '../utils/username.php';
include_once '../static/navbar.php';
?>
<section class="text-center">
    <?php if($verified): ?>
        <h1 class="display-4">Email verified</h1>
        <p class="lead">You can now <a href="register.php">login</a> </p>
    <?php elseif($invalid_jwt): ?>
        <h1 class="display-4">Invalid token</h1>
        <p class="lead">The token is invalid or has expired</p>
    <?php elseif($to_verify): ?>
        <h1 class="display-4">Pending verification</h1>
        <p class="lead">You should have received a verification email.</p>
        <p class="lead">If not, or the verification code has expired(1d), click <a href="email.php?sendemail=<?=$email?>">here</a> to resend the email</p>
    <?php elseif($sent): ?>
        <h1 class="display-4">Verification email sent</h1>
        <p class="lead">Check you email and also the spam folder</p>
        <p class="lead">If you cannot find it, or the verification code has expired(1d), click <a href="email.php?sendemail=<?=$email?>">here</a> to resend the email</p>
        <p class="lead">Email sent to <?=$email?></p>
    <?php else: ?>
        <h1 class="display-4">Go to login</h1>
        <p class="lead">Try to login in order to verify your email</p>
    <?php endif; ?>
</section>
<?php
include_once '../static/footer.php';
?>
</body>
</html>