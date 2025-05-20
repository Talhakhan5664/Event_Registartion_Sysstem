<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'event_registration_system');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the registration ID from the URL
$registration_id = intval($_GET['id']);

// Fetch the event ID and reduce participant count
$sql = "SELECT event_id FROM registrations WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $registration_id);
$stmt->execute();
$result = $stmt->get_result();
$registration = $result->fetch_assoc();

if ($registration) {
    $event_id = $registration['event_id'];

    // Delete the registration
    $delete_sql = "DELETE FROM registrations WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $registration_id);

    if ($delete_stmt->execute()) {
        // Reduce the participant count
        $update_sql = "UPDATE events SET current_participants = current_participants - 1 WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $event_id);
        $update_stmt->execute();

        echo "Registration canceled successfully.";
    } else {
        echo "Error canceling registration.";
    }
} else {
    echo "Registration not found.";
}

// Close the database connection
$conn->close();
?>
