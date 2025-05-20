<?php

// Include database connection
include 'db.php';

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer files
require 'PHPmailer/Exception.php';
require 'PHPmailer/PHPMailer.php';
require 'PHPmailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture user input and sanitize
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    // Input validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format');</script>";
    } elseif (strlen($password) < 6) {
        echo "<script>alert('Password must be at least 6 characters long');</script>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the user into the database
        $stmt = $conn->prepare("INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $username, $hashedPassword, $role);

        if ($stmt->execute()) {
            // Send email notification
            $mail = new PHPMailer(true);

            try {
                // PHPMailer settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'tariqtalha213@gmail.com'; // Your email
                $mail->Password = 'ysgs wtom oazb wnyr';     // Your email app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                // Recipients
                $mail->setFrom('tariqtalha213@gmail.com', 'Event Management');
                $mail->addAddress($email, $username);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Registration Successful';
                $mail->Body = "
                    <h1>Welcome, $username!</h1>
                    <p>Thank you for registering on our platform. You can now log in using your credentials.</p>
                    <p><a href='http://localhost/events/login.php'>Click here to log in</a></p>
                    <p>Best regards,<br>Event Management Team</p>
                ";

                $mail->send();
            } catch (Exception $e) {
                error_log("Email Error: {$mail->ErrorInfo}");
            }

            // Redirect to login page
            echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close(); // Close the statement
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
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
            color: #fff;
            padding: 20px 10px;
            text-align: center;
        }
        main {
            padding: 20px;
        }
        section {
            max-width: 400px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }
        .login-link {
            margin-top: 20px;
            text-align: center;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #343a40;
            color: #fff;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>User Registration</h1>
    </header>

    <!-- Registration Form -->
    <main>
        <section>
            <h2>Create an Account</h2>

            <form action="" method="POST">
                <!-- Email -->
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <!-- Username -->
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <!-- Password -->
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <!-- Role Selection -->
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="participant">Participant</option>
                    <option value="admin">Admin</option>
                </select>

                <!-- Register Button -->
                <button type="submit">Register</button>
            </form>

            <!-- Login Link -->
            <div class="login-link">
                <p>Already have an account?</p>
                <a href="login.php">Log in</a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Event Registration System</p>
    </footer>

</body>
</html>
