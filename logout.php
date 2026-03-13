<?php
session_start();
unset($_SESSION['email']);
unset($_SESSION['logged_in']);
session_destroy();
header('Location: login.php');
exit();
