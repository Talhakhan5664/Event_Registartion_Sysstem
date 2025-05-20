<?php
// Include the database connection file
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify user credentials
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: admin/events.php");
        } elseif ($user['role'] === 'participant') {
            header("Location: index.php");
        }
        exit;
    } else {
        $error_message = "Invalid email or password!";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        main {
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 100px);
        }

        section {
            max-width: 400px;
            width: 100%;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        form input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
            font-size: 1rem;
        }

        form button {
            margin-top: 20px;
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
            font-size: 0.9rem;
            text-align: center;
        }

        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #343a40;
            color: white;
        }

        footer a {
            color: #007bff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Event Registration System</h1>
    </header>

    <!-- Login Form -->
    <main>
        <section>
            <h2 style="text-align: center; color: #007bff; margin-bottom: 20px;">Login to Your Account</h2>

            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                <!-- Email -->
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <!-- Password -->
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <!-- Submit Button -->
                <button type="submit" name="login">Login</button>

                <!-- Register Link -->
                <p style="text-align: center; margin-top: 15px; font-size: 0.9rem; color: #555;">
                    Don't have an account? <a href="register.php">Register here</a>
                </p>
            </form>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Event Registration System</p>
    </footer>

</body>
</html>
