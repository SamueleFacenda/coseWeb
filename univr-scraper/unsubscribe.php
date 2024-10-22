<?php 

$email = $_GET['email'];
$orario = $_GET['orario'];

include_once 'db.php';

$stmt = $conn->prepare("DELETE FROM subscriptions WHERE email = ? AND orario = ?");
$stmt->bind_param("si", $email, $orario);
$stmt->execute();

$stmt->close();
$conn->close();

?>

<h1>Unsubscribed</h1>