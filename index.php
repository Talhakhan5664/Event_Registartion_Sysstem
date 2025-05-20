<?php
include 'db.php';

session_start();

// Example: Ensure user is logged in and email is stored in session
if (!isset($_SESSION['email'])) {
    header("location: login.php");
    exit;
}

// Fetch upcoming events
$sql_upcoming = "SELECT id, name, description, date, location FROM events WHERE date >= CURDATE() ORDER BY date ASC";
$result_upcoming = $conn->query($sql_upcoming);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>
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

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f8fe;
            color: #333;
        }
        header {
            background-color: #457b9d;
            color: #fff;
            padding: 40px 20px;
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
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .event-card {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        .event-card h3 {
            margin: 0 0 10px;
            color: #1d3557;
            font-size: 1.8rem;
        }
        .event-card p {
            margin: 5px 0;
            font-size: 1rem;
            color: #555;
        }
        .event-card button {
            background-color: #1d3557;
            color: white;
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }
        .event-card button:hover {
            background-color: #457b9d;
        }
        footer {
            text-align: center;
            padding: 20px 10px;
            background-color: #343a40;
            color: white;
        }
        footer p {
            margin: 0;
            font-size: 1rem;
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
            <a href="dashboard.php">Dashboard</a>
            <a href="registration.php">Register</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </nav>

    <!-- Header -->
    <header>
        <h1>Upcoming Events</h1>
        <p>Explore and Register for Upcoming Events</p>
    </header>

    <!-- Upcoming Events Section -->
    <main>
        <section>
            <h2 style="color: #1d3557;">Upcoming Events</h2>
            <?php if ($result_upcoming->num_rows > 0): ?>
                <?php while ($row = $result_upcoming->fetch_assoc()): ?>
                    <div class="event-card">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($row['date']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                        <button onclick="location.href='registration.php?id=<?php echo $row['id']; ?>'">
                            Register Now
                        </button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: #888;">No upcoming events available.</p>
            <?php endif; ?>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Event Registration System</p>
    </footer>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
