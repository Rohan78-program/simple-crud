<?php
include 'components/common.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <div class="container mt-5 w-50">
        <div id="form_message"></div>
        <div id="form_message"></div>
        <h2 class="mb-4 text-center">Login HERE</h2>
        <form class="ajax-form" method="post" action="login_process.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="email" class="form-label">User Name</label>
                <input type="text" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback">Enter a valid user name.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                <div class="invalid-feedback">Choose a password (min 6 characters).</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <p class="text-center mt-3">
                <a href="forgot_password.php">Forgot Password?</a>
            </p>
            <p class="text-center mt-3">
                Don't have an account? <a href="register.php">Register here</a>
        </form>
    </div>

    <script>
        $(document).on('submit', '.ajax-form', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let formData = form.serialize();

            form.find('.invalid-feedback').text('').hide();
            form.find(".form-control").removeClass('is-invalid');
            $('#form_message').html('');

            // show responses using ajax
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',

                beforeSend: function() {
                    form.find('button[type="submit"]').prop('disabled', true).text('Logging in...');
                },

                success: function(response) {
                    if (response.success) {
                        $('#form_message').html('<div class="alert alert-success">' + response.message + '</div>');
                        form[0].reset();
                        window.location.href = 'dashboard.php';
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
                    form.find('button[type="submit"]').prop('disabled', false).text('Login');
                }

            });
        });
    </script>
</body>

</html>