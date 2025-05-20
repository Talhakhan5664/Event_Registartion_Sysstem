<?php
include '../db.php';

if (isset($_GET['id'])) {
    $event_id = (int)$_GET['id'];
    $sql = "SELECT * FROM events WHERE id = $event_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        die("Event not found");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $max_participants = (int)$_POST['max_participants'];
    $description = $_POST['description'];
    $category=$_POST['category'];

    $sql = "UPDATE events SET name='$name', date='$date', location='$location', max_participants=$max_participants, description='$description', category='$category' WHERE id=$event_id";

    if ($conn->query($sql)) {
        header("Location: events.php?success=Event updated successfully");
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
    <title>Edit Event</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        .container h1 {
            font-size: 1.8rem;
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
            color: #555;
        }

        input, textarea {
            font-family: 'Roboto', sans-serif;
            font-size: 1rem;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
            width: 100%;
            transition: all 0.3s ease;
        }

        input:focus, textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        textarea {
            resize: none;
            height: 120px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            font-size: 1rem;
            font-weight: 500;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Event</h1>
        <form method="POST" action="">

        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" required style="width: 100%; padding: 10px; margin: 10px 0;" >
                <option value="Workshop">Workshop</option>
                <option value="Seminar">Seminar</option>
                <option value="Concert">Concert</option>
            </select>
            </div>

            <div class="form-group">
                <label for="name">Event Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" value="<?php echo $event['date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
            </div>

            <div class="form-group">
                <label for="max_participants">Maximum Participants:</label>
                <input type="number" id="max_participants" name="max_participants" value="<?php echo $event['max_participants']; ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>

            <button type="submit">Update Event</button>
        </form>
    </div>
</body>
</html>

