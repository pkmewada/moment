<?php

header("Content-Type: application/json; charset=utf-8");

function send_json($success, $message, $statusCode = null) {
    http_response_code($statusCode ?? ($success ? 200 : 400));
    echo json_encode([
        "success" => $success,
        "message" => $message
    ]);
    exit;
}

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

if ($name === "" || $phone === "" || $event_date === "") {
    send_json(false, "Please fill all required fields.");
}

if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_json(false, "Please enter a valid email address.");
}

if (
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $event_date) ||
    !checkdate((int) substr($event_date, 5, 2), (int) substr($event_date, 8, 2), (int) substr($event_date, 0, 4))
) {
    send_json(false, "Please enter a valid event date.");
}

$recipient = "pp8754352@gmail.com";
$subject = "New booking request - Moments by MQlus";
$body = implode("\r\n", [
    "A new booking request was submitted on the Moments by MQlus website.",
    "",
    "Name: " . $name,
    "Email: " . ($email !== "" ? $email : "Not provided"),
    "Phone: " . $phone,
    "Event type: " . ($event_type !== "" ? $event_type : "Not provided"),
    "City: " . ($city !== "" ? $city : "Not provided"),
    "Event date: " . $event_date,
    "Address / venue: " . ($address !== "" ? $address : "Not provided"),
    "",
    "Additional details:",
    $message !== "" ? $message : "None"
]);

$headers = [
    "From: Moments by MQlus <connect@mqlus.in>",
    "MIME-Version: 1.0",
    "Content-Type: text/plain; charset=UTF-8"
];

if ($email !== "") {
    $headers[] = "Reply-To: " . $email;
}

if (!mail($recipient, $subject, $body, implode("\r\n", $headers))) {
    error_log("Booking notification email could not be sent.");
    send_json(
        false,
        "We couldn't send your request right now. Please try again or email us at connect@mqlus.in.",
        500
    );
}

send_json(true, "Booking request sent successfully!");

?>
