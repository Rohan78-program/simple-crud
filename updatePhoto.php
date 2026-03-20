<?php
include 'connection/connect.php';

$response = ['success' => 'false', 'error' => ''];

$email = htmlspecialchars($_POST['email']);

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
            //photo uploaded in folder
            move_uploaded_file($tmpName, "uploads/" . $photoName);
        } else {

            $response['error'] = "Image too large";
        }
    } else {

        $response['error'] = "Invalid image format";
    }
} else {
    $response['error'] = "Please select Image ";
}

//if there is no error, proceed with database operations
if (empty($response['error'])) {

    $stmt = $conn->prepare("UPDATE user SET photo=? WHERE email=?");
    $stmt->bind_param("ss", $photoName, $email);

    if ($stmt->execute() === true) {
        $response['success'] = true;
    } else {
        $response['error'] = "Failed to Update photo. (DB error: " . $conn->error . ")";
    }
}

echo json_encode($response);
