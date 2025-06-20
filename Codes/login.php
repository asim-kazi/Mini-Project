<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user details from database
    $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt_result = $stmt->get_result();

    if ($stmt_result->num_rows > 0) {
        $data = $stmt_result->fetch_assoc();

        // Verify password
        if (password_verify($password, $data['password_hash'])) {
            // Store all user details in session
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['full_name'] = $data['full_name'];
            $_SESSION['position'] = $data['position'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['phone_number'] = $data['phone_number'];
            $_SESSION['country'] = $data['country'];
            $_SESSION['city'] = $data['city'];
            $_SESSION['gender'] = $data['gender'];

            // Redirect to profile page
            header("Location: profile.php");
            exit();
        } else {
            echo "Invalid email or password!";
        }
    } else {
        echo "Invalid email or password!";
    }
}
?>
