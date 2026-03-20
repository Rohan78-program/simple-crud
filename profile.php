<?php
require 'connection/connect.php';
include 'components/common.php';
session_start();
if (!isset($_SESSION['email']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
$sql = "SELECT * FROM user WHERE email='" . $_SESSION['email'] . "'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

//retrive image from database
if ($user['photo']) {
    $photoPath = "uploads/" . $user['photo'];
} else {
    $photoPath = "uploads/default-profile.png";
}

//set time zone
date_default_timezone_set('Asia/Kolkata');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        #edit-photo {
            z-index: 1;
            position: relative;
            bottom: -30px;
            right: 40px;
            cursor: pointer;
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <div class="container mt-5 w-50">
        <h2 class="mb-4 text-center">
            <img src="<?php echo $photoPath; ?>" class="rounded-circle" width="100" height="100" alt="Profile Picture">
            <a href="updatePhoto.php" id="edit-photo" title="Edit Profile Picture" data-bs-toggle="modal" data-bs-target="#photoModal">
                <img src="photo-camera.png" style="width: 40px;height: 40px;" alt="edit profile picture">
            </a>
        </h2>
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

    <div>
        <!-- Toast for success/ error message -->

        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="logo.png" class="rounded me-2" alt="logo" width="20" height="20">
                    <strong class="me-auto">CRUD</strong>
                    <small><?= date('h:i A', time()) ?></small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div id="toast-msg" class="toast-body">

                </div>
            </div>
        </div>
    </div>

    <!-- modal for update profile picture -->
    <div class="modal fade" id="photoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="updatePhoto.php" class="ajaxForm" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="photoModalLabel">Update Profile Picture</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img src="<?php echo $photoPath; ?>" class="rounded-circle" width="100" height="100" alt="Profile Picture">

                        <div class="mb-3 mt-3">
                            <img src="add-photo.png" alt="add photo" style="width: 40px;height: 40px;cursor: pointer;" onclick="document.getElementById('photo').click();">
                            <label for="photo">Change Photo</label>
                            <input class="form-control" type="file" id="photo" name="photo" hidden>
                            <input type="text" name="email" value="<?php echo $_SESSION['email']; ?>" hidden>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <!--Script-->

    <script>
        const toastEl = document.getElementById('liveToast');
        const toast = new bootstrap.Toast(toastEl, {
            delay: 5000
        });

        $(document).on('submit', '.ajaxForm', function(e) {
            e.preventDefault();
            $('#toast-msg').text('');

            const form = $(this);
            const url = form.attr('action');
            const formData = new FormData(this);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',

                beforeSend: function() {
                    form.find('button[type="submit"]').prop('disabled', true).text('Uploading...');
                },

                success: function(response) {
                    if (response.success) {
                        $('#toast-msg').text('Profile Photo Updated Successfully.');
                        toast.show();
                        form[0].reset();
                        setTimeout(function() {
                            window.location.href = 'profile.php';
                        }, 5000);
                    } else {
                        $('#toast-msg').text(response.error || 'Something went wrong');
                        toast.show();
                    }
                },

                error: function() {
                    $('#toast-msg').text('Something went wrong');
                    toast.show();
                },

                complete: function() {
                    form.find('button[type="submit"]').prop('disabled', false).text('Upload');
                }

            });
        });
    </script>

</body>

</html>