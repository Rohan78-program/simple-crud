<?php
require 'connection/connect.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'error' => []
];

$name = trim(htmlspecialchars($_POST['name'] ?? ''));
$email = trim(htmlspecialchars($_POST['email'] ?? ''));
$phone = trim(htmlspecialchars($_POST['phone'] ?? ''));
$address = trim(htmlspecialchars($_POST['address'] ?? ''));

if (empty($name) || !preg_match('/^[a-zA-Z ]+$/', $name)) {
    $response['error']['name'] = 'Name is required and should only contain letters and spaces.';
}

if (empty($phone) || !preg_match('/^[0-9]{10}$/', $phone)) {
    $response['error']['phone'] = 'Valid phone number is required (10 digits).';
}

if (empty($address)) {
    $response['error']['address'] = 'Address is required.';
}

if (empty($response['error'])) {
    $stmt = $conn->prepare('SELECT name, phone, address FROM user WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare('UPDATE user SET name = ?, phone = ?, address = ? WHERE email = ?');
        $stmt->bind_param('ssss', $name, $phone, $address, $email);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Profile updated successfully.';
        } else {
            $response['error']['general'] = 'Failed to update profile. Please try again. (DB error: ' . $conn->error . ')';
        }
    } else {
        $response['error']['general'] = 'User not found.';
    }
}

echo json_encode($response);
