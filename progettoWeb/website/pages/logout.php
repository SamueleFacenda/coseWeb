<?php

// delete jwt
setcookie('jwt', '', time() - 3600, '/');
// redirect to home
header('location: index.php');