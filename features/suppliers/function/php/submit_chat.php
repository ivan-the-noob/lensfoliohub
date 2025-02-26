<?php
session_start();

require '../../../../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_SESSION['email']) ? $conn->real_escape_string($_SESSION['email']) : null;
    $click_email = isset($_POST['click_email']) ? $conn->real_escape_string($_POST['click_email']) : null;
    $text = isset($_POST['text']) ? $conn->real_escape_string($_POST['text']) : null;

    if (!empty($email)) {
        $roleQuery = "SELECT role FROM users WHERE email = '$email'";
        $roleResult = $conn->query($roleQuery);

        if ($roleResult->num_rows > 0) {
            $roleRow = $roleResult->fetch_assoc();
            $role = $roleRow['role'];
        } else {
            die("No user found with email: " . $email);
        }
    } else {
        die("Session email is missing.");
    }

    if (!empty($click_email) && !empty($text)) {
        // Insert the chat message
        $sql = "INSERT INTO chat (email, uploader_email, text, role) 
                VALUES ('$email', '$click_email', '$text', '$role')";

        if ($conn->query($sql) === TRUE) {
            // Insert a notification for the recipient (click_email)
            $message = "New message! Check it out! New message check it out!";
           

            $notificationSql = "INSERT INTO notification (email, email_uploader, message) 
                                VALUES ('$email', '$click_email', '$message')";

            if ($conn->query($notificationSql) === TRUE) {
                // Send the response back
                echo json_encode([
                    'email' => $email,
                    'text' => $text,
                    'role' => $role,
                    'timestamp' => time(),
                ]);
            } else {
                echo json_encode(['error' => 'Failed to insert notification']);
            }
        } else {
            echo json_encode(['error' => 'Failed to insert message']);
        }
    } else {
        echo json_encode(['error' => 'All fields are required']);
    }
}

$conn->close();
?>