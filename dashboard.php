<?php
include 'db.php';

session_start();

// Example: Ensure user is logged in and email is stored in session
if (!isset($_SESSION['email'])) {
    header("location: login.php");
    exit;
}

$user_email = $_SESSION['email']; // Get the user's email from session

// Fetch participant details (from registrations table)
$sql_participant = "SELECT name, email, phone FROM registrations WHERE email = ?";
$stmt_participant = $conn->prepare($sql_participant);
$stmt_participant->bind_param("s", $user_email);
$stmt_participant->execute();
$result_participant = $stmt_participant->get_result();

// Fetch events the user has registered for
$sql = "
    SELECT 
        r.id AS registration_id, 
        e.name AS event_name, 
        e.date, 
        e.location 
    FROM registrations r
    JOIN events e ON r.event_id = e.id
    WHERE r.email = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Navbar styles */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1d3557;
            padding: 15px 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Logo styles */
        .logo {
            font-size: 1.8rem;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
        }

        /* Navbar links container */
        .nav-links {
            display: flex;
            gap: 20px;
        }

        /* Navbar link styles */
        .nav-links a {
            color: white;
            font-size: 1.1rem;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        /* Hover effect for links */
        .nav-links a:hover {
            background-color: #457b9d;
        }

        /* Optional Logout button style */
        .logout {
            background-color: #e74c3c;
            padding: 10px 15px;
            border-radius: 5px;
            color: white;
            font-size: 1.2rem;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .logout:hover {
            background-color: #c0392b;
        }

        /* Body & Section Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        header {
            background-color: #457b9d;
            color: white;
            padding: 20px 20px;
            margin-top: 5px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
        }
        header p {
            font-size: 1.2rem;
            margin-top: 10px;
        }
        main {
            padding: 40px 20px;
        }
        section {
            max-width: 1200px;
            margin: auto;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #c82333;
        }
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #343a40;
            color: white;
        }
        footer p {
            margin: 0;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav>
        <!-- Logo Section -->
        <a href="dashboard.php" class="logo">EventHub</a>

        <!-- Right Side Links -->
        <div class="nav-links">
            <a href="index.php">Upcomming Events</a>
            <a href="registration.php">Register</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </nav>

    <!-- Header -->
    <header>
        <h1>Participant Dashboard</h1>
        <p>Your Registered Events</p>
    </header>

    <!-- Main Content -->
    <main>
        <section>
            <h2 style="color: #007bff;">Your Details</h2>
            <?php if ($result_participant->num_rows > 0): ?>
                <?php $row = $result_participant->fetch_assoc(); ?>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?></p>
            <?php else: ?>
                <p>No participant details found.</p>
            <?php endif; ?>

            <h2 style="color: #007bff; margin-top: 40px;">Your Registered Events</h2>
            <div class="table-container">
                <?php if ($result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                                    <td>
                                        <button onclick="cancelRegistration(<?php echo $row['registration_id']; ?>)">
                                            Cancel
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: #999;">You have not registered for any events yet.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Event Management System</p>
    </footer>

    <script>
        function cancelRegistration(registrationId) {
            if (confirm("Are you sure you want to cancel this registration?")) {
                // Redirect to a cancellation handler script
                location.href = `cancel_registration.php?id=${registrationId}`;
            }
        }
    </script>

</body>
</html>
