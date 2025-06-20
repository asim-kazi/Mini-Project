<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];
$position = $_SESSION['position'];
$email = $_SESSION['email'];
$phone_number = $_SESSION['phone_number'];
$country = $_SESSION['country'];
$city = $_SESSION['city'];
$gender = $_SESSION['gender'];

// Initialize livestock data
$milk = $wool = $egg = "N/A";

// Fetch livestock data if the user is a farmer
if ($position == "farmer") {
    $stmt = $con->prepare("SELECT milk_quantity, wool_quantity, egg_quantity FROM livestock WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt_result = $stmt->get_result();

    if ($stmt_result->num_rows > 0) {
        $livestock = $stmt_result->fetch_assoc();
        $milk = $livestock['milk_quantity'];
        $wool = $livestock['wool_quantity'];
        $egg = $livestock['egg_quantity'];
    }
}

// Manager dashboard view
if ($position == "manager") {
    // Fetch all farmers' data (or any specific list you need)
    $stmt = $con->prepare("SELECT id, full_name, position, email FROM users WHERE position = 'farmer'");
    $stmt->execute();
    $farmers_result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>
</head>
<body>
    <header class="animate__animated animate__bounceInLeft">
        <h1>Dairy Farmers Dashboard</h1>
        <nav>
            <a href="profile.php">Home</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <section class="dashboard-container">
            <h2>Welcome, <?php echo htmlspecialchars($full_name); ?>!</h2>

            <?php if ($position == "manager") { ?>
                <div class="manager-dashboard">
                    <h3>Manager Dashboard</h3>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($phone_number); ?></p>
                    <p><strong>Country:</strong> <?php echo htmlspecialchars($country); ?></p>
                    <p><strong>City:</strong> <?php echo htmlspecialchars($city); ?></p>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($gender); ?></p>

                    <h3>Farmers List</h3>
                    <table class="farmers-table">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>

                        <?php while ($farmer = $farmers_result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($farmer['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($farmer['email']); ?></td>
                                <td><a href="update_livestock.php?farmer_id=<?php echo $farmer['id']; ?>">Update Livestock</a></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>

            <?php } elseif ($position == "farmer") { ?>
                <div class="farmer-dashboard">
                    <h3>Farmer Dashboard</h3>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($phone_number); ?></p>
                    <p><strong>Country:</strong> <?php echo htmlspecialchars($country); ?></p>
                    <p><strong>City:</strong> <?php echo htmlspecialchars($city); ?></p>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($gender); ?></p>

                    <h3>Livestock Data</h3>
                    <p><strong>Milk Quantity:</strong> <?php echo $milk; ?> Liters</p>
                    <p><strong>Wool Quantity:</strong> <?php echo $wool; ?> Kg</p>
                    <p><strong>Egg Quantity:</strong> <?php echo $egg; ?> Units</p>
                </div>

            <?php } else { ?>
                <p>You don't have permission to access this dashboard.</p>
            <?php } ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 All Rights Reserved To C.A.M</p>
    </footer>

</body>
</html>
