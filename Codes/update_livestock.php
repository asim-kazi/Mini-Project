<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is a manager
if (!isset($_SESSION['user_id']) || $_SESSION['position'] != 'manager') {
    header("Location: sign_in.html");
    exit();
}

// Get the farmer's ID from the URL
if (isset($_GET['farmer_id'])) {
    $farmer_id = $_GET['farmer_id'];

    // Fetch the current livestock values for the farmer
    $stmt = $con->prepare("SELECT milk_quantity, wool_quantity, egg_quantity FROM livestock WHERE user_id = ?");
    $stmt->bind_param("i", $farmer_id);
    $stmt->execute();
    $stmt_result = $stmt->get_result();

    if ($stmt_result->num_rows > 0) {
        $livestock = $stmt_result->fetch_assoc();
    } else {
        echo "No livestock data found for this farmer.";
        exit();
    }
} else {
    echo "Farmer ID is required.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update livestock values
    $milk_quantity = $_POST['milk_quantity'];
    $wool_quantity = $_POST['wool_quantity'];
    $egg_quantity = $_POST['egg_quantity'];

    $stmt = $con->prepare("UPDATE livestock SET milk_quantity = ?, wool_quantity = ?, egg_quantity = ? WHERE user_id = ?");
    $stmt->bind_param("iiii", $milk_quantity, $wool_quantity, $egg_quantity, $farmer_id);

    if ($stmt->execute()) {
        echo "Livestock data updated successfully.";
    } else {
        echo "Error updating data: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Livestock</title>
    <style>
        /* Reset some default styles */
        body, h1, h2, h3, p, table, form {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        /* Set background image */
        body {
            background-image: url('Bg_1.jpg'); /* Use your actual image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column; /* Allow footer to stay at the bottom */
            justify-content: flex-start;
        }

        /* Navbar Styling */
        header {
            background: #2e8b57; /* Dark green background */
            color: white;
            padding: 20px 0;
            width: 100%;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
            text-align: center;
        }

        header nav {
            text-align: center;
            margin-top: 10px;
        }

        header nav a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            font-size: 1.2rem;
            padding: 5px 10px;
            transition: background-color 0.3s ease;
        }

        header nav a:hover {
            background-color: #45a049; /* Hover color */
            border-radius: 5px;
        }

        /* Sign-In Container Styling */
        .form-container {
            background: rgba(255, 255, 255, 0.9); /* Slight transparency */
            padding: 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            text-align: center;
            margin: 20px;
            backdrop-filter: blur(10px); /* Background blur effect */
            position: relative;
            left: 29.5%;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 1rem;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        button.btn {
            width: 100%;
            padding: 14px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
        }

        button.btn:hover {
            background: #45a049; /* Darker green on hover */
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: auto; /* Push footer to the bottom */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                max-width: 90%;
                padding: 20px;
            }

            h2 {
                font-size: 1.6rem;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                max-width: 100%;
                padding: 15px;
            }

            h2 {
                font-size: 1.5rem;
            }

            button.btn {
                font-size: 1rem;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header>
        <h1 class="animate__animated animate__bounceInLeft">Dairy Farmers Dashboard</h1>
        <nav>
            <a href="profile.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <!-- Form for Updating Livestock -->
    <div class="form-container">
        <h2>Update Livestock for Farmer</h2>
        <form method="POST">
            <div class="form-group">
                <label for="milk_quantity">Milk Quantity (in liters):</label>
                <input type="number" id="milk_quantity" name="milk_quantity" value="<?php echo $livestock['milk_quantity']; ?>" required>
            </div>

            <div class="form-group">
                <label for="wool_quantity">Wool Quantity (in kg):</label>
                <input type="number" id="wool_quantity" name="wool_quantity" value="<?php echo $livestock['wool_quantity']; ?>" required>
            </div>

            <div class="form-group">
                <label for="egg_quantity">Egg Quantity (in units):</label>
                <input type="number" id="egg_quantity" name="egg_quantity" value="<?php echo $livestock['egg_quantity']; ?>" required>
            </div>

            <button type="submit" class="btn">Update Livestock</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2025 All Rights Reserved To C.A.M</p>
    </footer>
</body>
</html>
