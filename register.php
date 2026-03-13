<?php
include 'components/common.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
</head>

<body>
    <!-- registration container -->
    <div class="container mt-5 w-50">
        <div id="form_message"></div>
        <h2 class="mb-4 text-center">Create an Account</h2>
        <form class="ajax-form" method="post" action="registration_process.php" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Enter a valid email.</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="1234567890" required>
                <div class="invalid-feedback">Please provide a 10-digit phone number.</div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                <div class="invalid-feedback">Address cannot be empty.</div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                <div class="invalid-feedback">Choose a password (min 6 characters).</div>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Profile Photo</label>
                <input class="form-control" type="file" id="photo" name="photo">
                <div class="invalid-feedback"></div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
            <p class="text-center mt-3">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </form>
    </div>

    <script>
        $(document).on('submit', '.ajax-form', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let formData = new FormData(this);

            form.find('.invalid-feedback').text('').hide();
            form.find(".form-control").removeClass('is-invalid');
            $('#form_message').html('');

            // show responses using ajax
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',

                beforeSend: function() {
                    form.find('button[type="submit"]').prop('disabled', true).text('Registering...');
                },

                success: function(response) {
                    if (response.success) {
                        $('#form_message').html('<div class="alert alert-success">' + response.message + '</div>');
                        form[0].reset();
                        window.location.href = 'login.php';
                    } else {
                        if (response.error.general) {
                            $('#form_message').html('<div class="alert alert-danger">' + response.error.general + '</div>');
                        }
                        $.each(response.error, function(key, message) {
                            let input = form.find('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').text(message).show();
                        });
                    }
                },

                error: function() {
                    $('#form_message').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                },

                complete: function() {
                    form.find('button[type="submit"]').prop('disabled', false).text('Register');
                }

            });
        });
    </script>
</body>

</html>