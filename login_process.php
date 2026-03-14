<?php
require 'connection/connect.php';
session_start();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'error' => []];

$email = htmlspecialchars(trim($_POST['email'] ?? ''));
$password = htmlspecialchars(trim($_POST['password'] ?? ''));

if (empty($email) || empty($password)) {
    $response['error']['email'] = 'User Name is required.';
    $response['error']['password'] = 'Password is required.';
} else {
    $sql = $conn->prepare("SELECT * FROM user WHERE email = ? AND password = ?");
    $sql->bind_param("ss", $email, $password);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows > 0) {
        $response['success'] = true;
        $response['message'] = 'Login successful.';
        $_SESSION['email'] = $email;
        $_SESSION['logged_in'] = true;
    } else {
        $response['error']['general'] = 'Invalid Credentials. Please try again.';
    }
}

echo json_encode($response);
