<?php
include '../db.php';

session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin'){
    header('location: ../login.php');
    }

// Fetch events
$sql = "SELECT id, name, date, location, max_participants, current_participants, category FROM events";
$result = $conn->query($sql);

$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

$sql = "SELECT id, name, date, location, max_participants, current_participants, category FROM events";

if (!empty($categoryFilter)) {
    $stmt = $conn->prepare($sql . " WHERE category = ?");
    $stmt->bind_param("s", $categoryFilter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}


if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);

    // Delete query
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        echo "<script>alert('Event deleted successfully'); window.location.href='events.php';</script>";
    } else {
        echo "<script>alert('Failed to delete event'); window.location.href='events.php';</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; color: #333;">
    <style>
        /* Logo styles */
        .logo {
            font-size: 1.8rem;
            color: #007bff;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: #007bff;
        }
    </style>

    <!-- Header -->
    <header style="background-color: #343a40; color: #fff; padding: 15px; text-align: center; display: flex; justify-content: space-between; align-items: center;">
        <button><a href="events.php" class="logo"  >EventHub</a></button>
    <div>
        
        <h1 style="text-align: center; margin: 0; font-size: 2rem;  ">Admin Dashboard</h1>
        <p style="margin: 5px 0; font-size: 1rem; text-align: center; ">Manage Events and Participants</p>
    </div>
    
    <button onclick="location.href='../index.php'" 
            style="background-color:rgb(77, 35, 216); color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 1rem;">
        Upcoming Events
    </button>

    <button onclick="location.href='../logout.php'" 
            style="background-color: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 1rem;">
        Logout
    </button>
</header>

    <!-- Main Content -->
    <main style="padding: 20px;">
        <!-- Action Buttons -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 style="color: #007bff; margin: 0;">Event Management</h2>
    <form method="GET" style="display: inline-block; margin-right: 20px;">
        <select name="category" onchange="this.form.submit()" 
                style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            <option value="">All Categories</option>
            <option value="Workshop" <?php echo (isset($_GET['category']) && $_GET['category'] === 'Workshop') ? 'selected' : ''; ?>>Workshops</option>
            <option value="Seminar" <?php echo (isset($_GET['category']) && $_GET['category'] === 'Seminar') ? 'selected' : ''; ?>>Seminars</option>
            <option value="Concert" <?php echo (isset($_GET['category']) && $_GET['category'] === 'Concert') ? 'selected' : ''; ?>>Concerts</option>
        </select>
    </form>
    <button onclick="location.href='add_event.php'" 
            style="background-color: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 1rem;">
        + Create Event
    </button>
</div>

            <!-- Event Table -->
            <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background-color: #007bff; color: white;">
                            <tr>
                                <th style="padding: 10px;">Event Name</th>
                                <th style="padding: 10px;">Date</th>
                                <th style="padding: 10px;">Location</th>
                                <th style="padding: 10px;">Category</th>
                                <th style="padding: 10px;">Participants</th>
                                <th style="padding: 10px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr style="border-bottom: 1px solid #ddd;">
                                    <td style="padding: 10px;"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td style="padding: 10px;"><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td style="padding: 10px;"><?php echo htmlspecialchars($row['location']); ?></td>
                                    <td style="padding: 10px;"><?php echo htmlspecialchars($row['category']); ?></td>
                                    <td style="padding: 10px; text-align: center;">
                                        <?php echo $row['current_participants'] . '/' . $row['max_participants']; ?>
                                    </td>
                                    <td style="padding: 10px;">
                                        <button onclick="location.href='edit_event.php?id=<?php echo $row['id']; ?>'" 
                                                style="background-color: #ffc107; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 0.9rem;">
                                            Edit
                                        </button>
                                        <button 
                                                onclick="if(confirm('Are you sure you want to delete this event?')) window.location.href='events.php?delete_id=<?php echo $row['id']; ?>';" 
                                                style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 0.9rem;">
                                            Delete
                                        </button>
                                        <button onclick="location.href='view_participants.php?id=<?php echo $row['id']; ?>'" 
                                                style="background-color: #17a2b8; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 0.9rem;">
                                            View Participants
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                </table>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer style="text-align: center; padding: 10px 0; background-color: #343a40; color: #fff; margin-top: 20px;">
        <p style="margin: 0;">&copy; 2025 Event Management System</p>
    </footer>


</body>
</html>
