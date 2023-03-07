<?php

include_once '../utils/connection.php';
connect();
$result = search_users($_GET['query']);
// return json
header('Content-Type: application/json');
echo json_encode($result);