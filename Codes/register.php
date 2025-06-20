<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full-name'];
    $position = $_POST['position'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone-number'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    if ($password !== $confirm_password) {
        die("Error: Passwords do not match!");
    }

    // Check if the email already exists
    $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt_result = $stmt->get_result();

    if ($stmt_result->num_rows > 0) {
        die("Error: Email already exists. Please use a different email.");
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insert user data
    $stmt = $con->prepare("INSERT INTO users (full_name, position, email, phone_number, country, city, gender, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $full_name, $position, $email, $phone_number, $country, $city, $gender, $password_hash);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // If the user is a farmer, insert livestock data
        if ($position == "farmer") {
            $stmt = $con->prepare("INSERT INTO livestock (user_id) VALUES (?)");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }

        $_SESSION['user_id'] = $user_id;
        $_SESSION['full_name'] = $full_name;
        $_SESSION['position'] = $position;
        $_SESSION['email'] = $email;
        $_SESSION['phone_number'] = $phone_number;
        $_SESSION['country'] = $country;
        $_SESSION['city'] = $city;
        $_SESSION['gender'] = $gender;

        header("Location: profile.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
