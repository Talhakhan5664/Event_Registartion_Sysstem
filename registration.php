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

// Get user info
$user_query = mysqli_query($conn, "SELECT username, email FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($user_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Check for duplicate registration
    $check_query = mysqli_query($conn, "SELECT * FROM registrations WHERE event_id = $event_id AND email = '$email'");
    if (mysqli_num_rows($check_query) > 0) {
        $message = "<p style='color:red;'>You are already registered for this event.</p>";
    } else {
        // Get event info
        $event_query = mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id");
        $event = mysqli_fetch_assoc($event_query);

        if ($event) {
            $spots_left = $event['max_participants'] - $event['current_participants'];

            if ($spots_left > 0) {
                // Register user
                $username = $user['username'];
                $insert_query = mysqli_query($conn, "INSERT INTO registrations (event_id, name, email, phone)
                                                     VALUES ($event_id, '$username', '$email', '$phone')");
                if ($insert_query) {
                    // Update participant count
                    $new_count = $event['current_participants'] + 1;
                    mysqli_query($conn, "UPDATE events SET current_participants = $new_count WHERE id = $event_id");

                    header("Location: dashboard.php");
                    exit;
                } else {
                    $message = "<p style='color:red;'>Error registering. Please try again.</p>";
                }
            } else {
                $message = "<p style='color:red;'>Event is full.</p>";
            }
        } else {
            $message = "<p style='color:red;'>Event not found.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Registration</title>
</head>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            color: #333;
        }
        header {
            background-color: #457b9d;
            color: white;
            padding: 3px 0;
            margin-top: 10px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        main {
            padding: 40px 10px;
        }
        section {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color:rgb(255, 255, 255);
        }
        h2 {
            font-size: 1.8rem;
            color: #3498db;
        }
        form {
            max-width: 600px;
            margin: auto;
        }
        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
            font-size: 1.1rem;
            color: #333;
        }
        input, select, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 1rem;
            color: #555;
        }
        input[type="text"], input[type="email"], select {
            background-color: #f9f9f9;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        footer {
            text-align: center;
            padding: 20px 10px;
            background-color: #2c3e50;
            color: white;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        .checkbox-container input {
            margin-right: 10px;
            width: auto;
        }
        .message {
            text-align: center;
            margin: 20px;
        }
        .message p {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Event Registration</h1>
        <p>Welcome, <?php echo htmlspecialchars($user['username']); ?>! Select an event to register.</p>
    </header>

    <main>
        <section>
            <?php if (isset($message)) echo "<div class='message'>$message</div>"; ?>

            <?php
            // Fetch available events
            $result_events = $conn->query("SELECT id, name FROM events WHERE max_participants > current_participants");

            if ($result_events->num_rows > 0) {
                ?>
                <form method="POST" action="">
                    <label for="event"> Select Event:</label>
                   <?php
                $selected_event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                ?>
                <select id="event" name="event_id" required>
                    <option value="">-- Select an Event --</option>
                    <?php
                    $result_events = $conn->query("SELECT id, name FROM events WHERE max_participants > current_participants");
                    while ($event = $result_events->fetch_assoc()) {
                        $selected = ($event['id'] == $selected_event_id) ? 'selected' : '';
                        echo '<option value="' . $event['id'] . '" ' . $selected . '>' . htmlspecialchars($event['name']) . '</option>';
                    }
                    ?>
                </select>


                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required placeholder="Enter your phone number">

                    <div class="checkbox-container">
                        <input type="checkbox" id="confirm" name="confirm" required>
                        <label for="confirm">I confirm my participation in this event</label>
                    </div>

                    <button type="submit">Register Now</button>
                </form>
                <?php
            } else {
                echo "<p style='text-align: center; font-size: 1.2rem;'>No events available for registration at the moment.</p>";
            }
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Event Registration System</p>
    </footer>
</body>
</html>
