<?php
require 'connection/connect.php';

header('Content-Type: application/json');

//response array to hold the success status and any error messages
$response = [
    'success' => false,
    'error' => []
];

// get the form data
$name = htmlspecialchars($_POST['name'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$phone = htmlspecialchars($_POST['phone'] ?? '');
$address = htmlspecialchars($_POST['address'] ?? '');
$password = htmlspecialchars($_POST['password'] ?? '');

$photoName = "";

if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {

    $fileName = $_FILES['photo']['name'];
    $tmpName = $_FILES['photo']['tmp_name'];
    $fileSize = $_FILES['photo']['size'];

    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($extension, $allowed)) {

        if ($fileSize <= 2 * 1024 * 1024) {

            $photoName = uniqid() . "." . $extension;

            move_uploaded_file($tmpName, "uploads/" . $photoName);
        } else {

            $response['error']['photo'] = "Image too large";
        }
    } else {

        $response['error']['photo'] = "Invalid image format";
    }
}

//validate name
if (empty($name) || !preg_match("/^[a-zA-Z ]*$/", $name)) {
    $response['error']['name'] = "Name is required and should only contain letters and spaces.";
}

//validate email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['error']['email'] = "Valid email is required.";
}

//validate phone
if (empty($phone) || !preg_match("/^[0-9]{10}$/", $phone)) {
    $response['error']['phone'] = "Valid phone number is required (10 digits).";
}

//validate address
if (empty($address)) {
    $response['error']['address'] = "Address is required.";
}

//validate password
if (empty($password) || strlen($password) < 6 || !preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", $password)) {
    $response['error']['password'] = "Password is required and should be at least 6 characters long.";
}



//if there is no error, proceed with database operations
if (empty($response['error'])) {
    //check if email already exists
    $exists = "SELECT email FROM user WHERE email = '$email'";

    if ($conn->query($exists)->num_rows > 0) {
        $response['error']['general'] = "Email already exists.";
    } else {

        //insert user into database
        $sql = "INSERT INTO user (name, email, phone, address, password, photo) VALUES ('$name', '$email', '$phone', '$address', '$password', '$photoName')";

        if ($conn->query($sql) === true) {
            $response['success'] = true;
            $response['message'] = "User successfully registered.";
        } else {
            $response['error']['general'] = "Failed to register user. Please try again. (DB error: " . $conn->error . ")";
        }
    }
}

//send the response back to the client
echo json_encode($response);
