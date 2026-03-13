<?php
include 'connection/connect.php';
include 'components/common.php';
session_start();
if (!isset($_SESSION['email']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$sql = "SELECT * FROM user WHERE email='" . $_SESSION['email'] . "'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard::CRuD</title>
</head>

<body>
    <div class="container mt-5">
        <h1>Welcome to the Dashboard :: <?= $user['name'] ?></h1>
        <p>This is a protected area. Only logged-in users can access this page.</p>
        <img class="img-thumbnail border border-primary float-end" src="uploads/<?= $user['photo'] ?>" alt="Profile Photo" width="150" height="150">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>

</html>