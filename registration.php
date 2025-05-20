<?php
// Database connection
include "db.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

// Fetch user details
$user_query = mysqli_query($conn, "SELECT username, email FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($user_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Check for existing registration
    $check_query = mysqli_query($conn, "SELECT * FROM registrations WHERE event_id = $event_id AND email = '$email'");
    if (mysqli_num_rows($check_query) > 0) {
        $message = "<p class='error'>You are already registered for this event.</p>";
    } else {
        // Get event details
        $event_query = mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id");
        $event = mysqli_fetch_assoc($event_query);

        if ($event) {
            $spots_left = $event['max_participants'] - $event['current_participants'];
            if ($spots_left > 0) {
                // Insert registration
                $name = $user['username'];
                $insert = mysqli_query($conn, "INSERT INTO registrations (event_id, name, email, phone) VALUES ($event_id, '$name', '$email', '$phone')");

                if ($insert) {
                    // Update count
                    $new_count = $event['current_participants'] + 1;
                    mysqli_query($conn, "UPDATE events SET current_participants = $new_count WHERE id = $event_id");

                    header("Location: dashboard.php");
                    exit;
                } else {
                    $message = "<p class='error'>Error during registration. Try again.</p>";
                }
            } else {
                $message = "<p class='error'>This event is already full.</p>";
            }
        } else {
            $message = "<p class='error'>Event not found.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
        }

        main {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h1, h2 {
            text-align: center;
        }

        form label {
            display: block;
            margin: 12px 0 6px;
            font-weight: bold;
        }

        form input, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #2980b9;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
        }

        .checkbox-container input {
            width: auto;
            margin-right: 10px;
        }

        .message, .error {
            text-align: center;
            font-size: 1.1rem;
            margin: 20px 0;
        }

        .error {
            color: red;
        }

        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 5px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

    </style>
</head>
<body>

<header>
    <h1>Event Registration</h1>
    <p>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</p>
</header>

<main>
    <?php if (isset($message)) echo "<div class='message'>$message</div>"; ?>

    <?php
    $selected_event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $event_result = mysqli_query($conn, "SELECT id, name FROM events WHERE max_participants > current_participants");

    if (mysqli_num_rows($event_result) > 0) {
    ?>
    <form method="POST" action="">
        <label for="event">Select Event:</label>
        <select name="event_id" id="event" required>
            <option value="">-- Choose an Event --</option>
            <?php while ($event = mysqli_fetch_assoc($event_result)) {
                $selected = ($event['id'] == $selected_event_id) ? 'selected' : '';
                echo "<option value='{$event['id']}' $selected>" . htmlspecialchars($event['name']) . "</option>";
            } ?>
        </select>

        <label for="name">Full Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" placeholder="Enter your phone number" required>

        <div class="checkbox-container">
            <input type="checkbox" name="confirm" required>
            <label for="confirm">I confirm my participation</label>
        </div>

        <button type="submit">Register Now</button>
    </form>
    <?php } else {
        echo "<p class='message'>No available events for registration.</p>";
    } ?>
</main>

<footer>
    <p>&copy; 2025 Event Registration System</p>
</footer>

</body>
</html>
