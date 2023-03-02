<?php

// delete jwt
setcookie('jwt', '', time() - 3600, '/');
// redirect to home
header('location: index.php');
echo 'flag{N00b_C1F3r}';