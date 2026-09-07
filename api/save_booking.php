<?php

header("Content-Type: application/json; charset=utf-8");

function send_json($success, $message) {
    http_response_code($success ? 200 : 400);
    echo json_encode([
        "success" => $success,
        "message" => $message
    ]);
    exit;
}

require_once __DIR__ . "/connection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    send_json(false, "Invalid request method.");
}

$name = trim($_POST["your-name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$event_type = trim($_POST["event-type"] ?? "");
$city = trim($_POST["city"] ?? "Indore");
$address = trim($_POST["address"] ?? "");
$event_date = trim($_POST["event-date"] ?? "");
$message = trim($_POST["message"] ?? "");

if (
    $name === "" ||
    $email === "" ||
    $phone === "" ||
    $event_type === "" ||
    $city === "" ||
    $address === "" ||
    $event_date === ""
) {
    send_json(false, "Please fill all required fields.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_json(false, "Please enter a valid email address.");
}

if (strtotime($event_date) === false) {
    send_json(false, "Please enter a valid event date.");
}

if ($conn instanceof mysqli) {
    $createTableSql = "CREATE TABLE IF NOT EXISTS bookings (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(50) NOT NULL,
        event_type VARCHAR(100) NOT NULL,
        city VARCHAR(120) NOT NULL,
        address TEXT NOT NULL,
        event_date DATE NOT NULL,
        message TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    if (!$conn->query($createTableSql)) {
        send_json(false, "Unable to prepare the booking table.");
    }

    $sql = "INSERT INTO bookings
           (name, email, phone, event_type, city, address, event_date, message)
           VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        send_json(false, "Database query error.");
    }

    $stmt->bind_param(
        "ssssssss",
        $name,
        $email,
        $phone,
        $event_type,
        $city,
        $address,
        $event_date,
        $message
    );

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        send_json(true, "Booking request sent successfully!");
    }

    $stmt->close();
    $conn->close();
    send_json(false, "Unable to save booking. Please try again.");
}

$backupPath = __DIR__ . "/bookings.json";
$entry = [
    "name" => $name,
    "email" => $email,
    "phone" => $phone,
    "event_type" => $event_type,
    "city" => $city,
    "address" => $address,
    "event_date" => $event_date,
    "message" => $message,
    "created_at" => date("Y-m-d H:i:s")
];

$existing = [];
if (file_exists($backupPath)) {
    $existing = json_decode(file_get_contents($backupPath), true);
    if (!is_array($existing)) {
        $existing = [];
    }
}

$existing[] = $entry;
file_put_contents($backupPath, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);

send_json(true, "Booking request sent successfully!");

?>