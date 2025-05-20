<?php
include '../db.php';

// Get event_id from the URL (from the 'view participants' button click)
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch participants for the event
$sql = "SELECT * FROM registrations WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch event details (to display event name)
$sql_event = "SELECT name FROM events WHERE id = ?";
$stmt_event = $conn->prepare($sql_event);
$stmt_event->bind_param("i", $event_id);
$stmt_event->execute();
$event_result = $stmt_event->get_result();
$event = $event_result->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Participants - <?php echo htmlspecialchars($event['name']); ?></title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; color: #333;">

    <!-- Header -->
    <header style="background-color: #343a40; color: #fff; padding: 20px; text-align: center;">
        <h1 style="margin: 0; font-size: 2rem;">View Participants - <?php echo htmlspecialchars($event['name']); ?></h1>
        <p style="margin: 5px 0; font-size: 1rem;">See who has registered for this event</p>
    </header>

    <!-- Main Content -->
    <main style="padding: 20px;">
        <!-- Participant Table -->
        <section style="max-width: 1200px; margin: auto;">
            <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead style="background-color: #007bff; color: white;">
                        <tr>
                            <th style="padding: 10px;">Participant Name</th>
                            <th style="padding: 10px;">Email</th>
                            <th style="padding: 10px;">Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px;"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td style="padding: 10px;"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td style="padding: 10px;"><?php echo htmlspecialchars($row['phone']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if ($result->num_rows == 0): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 10px;">No participants registered yet.</td>
                            </tr>
                        <?php endif; ?>
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

<?php
// Close database connection
$conn->close();
?>
