<?php
require 'connection/connect.php';
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
    <title>Profile</title>
</head>

<body>
    <div class="container mt-5 w-50">
        <h2 class="mb-4 text-center"><img src="uploads/<?php echo $user['photo']; ?>" alt="Profile Photo" class="img-fluid rounded-circle"></h2>
        <form class="ajax-form" method="post" action="#" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required readonly>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required readonly>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>" placeholder="1234567890" required readonly>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="2" required readonly><?php echo $user['address']; ?></textarea>
                <div class="invalid-feedback"></div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Profile</button>
        </form>
    </div>

</body>

</html>