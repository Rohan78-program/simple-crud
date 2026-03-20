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
    $sql = $conn->prepare("SELECT password FROM user WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();
    $storedHash = $result->fetch_assoc();
    if ($storedHash) {
        if (password_verify($password, $storedHash['password'])) {
            $response['success'] = true;
            $response['message'] = 'Login successful.';
            $_SESSION['email'] = $email;
            $_SESSION['logged_in'] = true;
        } else {
            $response['error']['general'] = 'Invalid Credentials. Please try again.';
        }
    } else {
        $response['error']['general'] = 'Invalid Credentials. Please try again.';
    }
}

echo json_encode($response);
