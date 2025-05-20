<?php

include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $max_participants = (int)$_POST['max_participants'];
    $description = $_POST['description'];
    $category=$_POST['category'];

    $sql = "INSERT INTO events (name, date, location, max_participants, description, category) VALUES ('$name', '$date', '$location', $max_participants, '$description', '$category' )";

    if ($conn->query($sql)) {
        header("Location: events.php?success=Event added successfully");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 0px; background-color: #f8f9fa;">

   <header style="background-color: #343a40; color: #fff; padding: 15px; margin-bottom:30px; text-align: center; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="text-align: center;">Create Event</h1>
    <a href="events.php" style="text-decoration: none; color: #333;"></a>

     </header>

    <form method="POST" action="" style="max-width: 600px; margin: auto; ">
        
        <label for="category">Category:</label>
    <select id="category" name="category" required style="width: 100%; padding: 10px; margin: 10px 0;">
        <option value="Workshop">Workshop</option>
        <option value="Seminar">Seminar</option>
        <option value="Concert">Concert</option>
    </select>
    
        <label for="name">Event Name:</label>
        <input type="text" id="name" name="name" required style="width: 100%; padding: 10px; margin: 10px 0;">

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required style="width: 100%; padding: 10px; margin: 10px 0;">

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required style="width: 100%; padding: 10px; margin: 10px 0;">

        <label for="max_participants">Maximum Participants:</label>
        <input type="number" id="max_participants" name="max_participants" required style="width: 100%; padding: 10px; margin: 10px 0;">

        <label for="description">Description:</label>
        <textarea id="description" name="description" required style="width: 100%; padding: 10px; margin: 10px 0;"></textarea>


        <button type="submit" name=""  style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Create Event</button>
    </form>
</body>
</html>
