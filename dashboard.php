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
<html>

<head>

    <title>User Dashboard</title>


</head>

<body>

    <div class="container-fluid">

        <div class="row">

            <!-- Sidebar -->
            <div class="col-md-2 sidebar">

                <div class="text-center mb-4">

                    <?php if ($user['photo']) { ?>
                        <img src="uploads/<?php echo $user['photo']; ?>" class="rounded-circle" width="100" height="100" alt="Profile Picture">
                    <?php } else { ?>
                        <img src="uploads/default-profile.png" class="rounded-circle" width="100" height="100" alt="Default Profile Picture">
                    <?php } ?>


                    <h5 class="mt-2"><?php echo $user['name']; ?></h5>

                </div>

                <a href="dashboard.php">Dashboard</a>
                <a href="profile.php">Profile</a>
                <a href="#">Settings</a>
                <a href="logout.php">Logout</a>

            </div>

            <!-- Main Content -->
            <div class="col-md-10">

                <nav class="navbar navbar-light bg-white shadow-sm mb-4">

                    <div class="container-fluid">

                        <span class="navbar-brand">Welcome <?php echo $user['name']; ?></span>

                    </div>

                </nav>

                <div class="container">

                    <div class="row">

                        <div class="col-md-4">

                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5>Total Orders</h5>
                                    <h3>12</h3>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5>Messages</h5>
                                    <h3>5</h3>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5>Notifications</h5>
                                    <h3>3</h3>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>