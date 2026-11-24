
<?php

header("Content-Type: application/json; charset=utf-8");

// JSON Response
function send_json($success, $message, $statusCode = null) {
    http_response_code($statusCode ?? ($success ? 200 : 400));

    echo json_encode([
        "success" => $success,
        "message" => $message
    ]);

    exit;
}

// Request Method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    send_json(false, "Invalid request method.");
}

// Form Data
$name       = trim($_POST["your-name"] ?? "");
$email      = trim($_POST["email"] ?? "");
$phone      = trim($_POST["phone"] ?? "");
$event_type = trim($_POST["event-type"] ?? "");
$city       = trim($_POST["city"] ?? "Indore");
$address    = trim($_POST["address"] ?? "");
$event_date = trim($_POST["event-date"] ?? "");
$message    = trim($_POST["message"] ?? "");

// Required Fields Validation
if ($name === "" || $phone === "" || $event_date === "") {
    send_json(false, "Please fill all required fields.");
}

// Email Validation
if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_json(false, "Please enter a valid email address.");
}

// Phone Validation
if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
    send_json(false, "Please enter a valid phone number.");
}

// Date Validation
if (
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $event_date) ||
    !checkdate(
        (int) substr($event_date, 5, 2),
        (int) substr($event_date, 8, 2),
        (int) substr($event_date, 0, 4)
    )
) {
    send_json(false, "Please enter a valid event date.");
}

// Email Configuration
$recipients = [
    "contact@mqlus.in",
    "hr@mqlus.in"
];

$recipient = "connect@mqlus.in";

$subject = "New Booking Inquiry | Moments by MQlus";

// Escape HTML Input
function e($value) {
    return htmlspecialchars(
        $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        "UTF-8"
    );
}

// Format Event Date
$formatted_date = date("d F Y", strtotime($event_date));

// Professional HTML Email
$html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Booking Inquiry</title>
</head>

<body style="margin:0; padding:0; background:#f3f4f6; font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0"
style="background:#f3f4f6; padding:35px 12px;">

<tr>
<td align="center">

<table width="600" cellpadding="0" cellspacing="0" border="0"
style="max-width:600px; width:100%; background:#ffffff; border-radius:12px; overflow:hidden;">

<!-- Header -->
<tr>
<td style="background:#171717; padding:35px 30px; text-align:center;">

<h1 style="margin:0; color:#d6b46a; font-size:27px; letter-spacing:1px;">
Moments <span style="color:#ffffff;">by MQlus</span>
</h1>

<p style="margin:10px 0 0; color:#c7c7c7; font-size:12px; letter-spacing:2px;">
CREATING MEMORABLE EXPERIENCES
</p>

</td>
</tr>

<!-- Main Heading -->
<tr>
<td style="padding:35px 30px 20px;">

<p style="margin:0 0 10px; color:#b18b43; font-size:12px; font-weight:bold; letter-spacing:1.5px;">
NEW BOOKING REQUEST
</p>

<h2 style="margin:0; color:#222222; font-size:24px;">
You have a new event inquiry!
</h2>

<p style="margin:12px 0 0; color:#666666; font-size:14px; line-height:23px;">
A potential customer has submitted a booking inquiry through your website.
Please review the details below and follow up with the customer.
</p>

</td>
</tr>

<!-- Customer Details -->
<tr>
<td style="padding:10px 30px 20px;">

<h3 style="margin:0 0 15px; color:#222222; font-size:16px;">
Customer Information
</h3>

<table width="100%" cellpadding="0" cellspacing="0"
style="background:#faf9f6; border:1px solid #eee9df; border-radius:8px;">

<tr>
<td style="padding:14px 16px; color:#888888; font-size:13px; width:35%;">
Full Name
</td>
<td style="padding:14px 16px; color:#222222; font-size:14px; font-weight:bold;">
' . e($name) . '
</td>
</tr>

<tr>
<td style="padding:14px 16px; color:#888888; font-size:13px; border-top:1px solid #eee9df;">
Email Address
</td>
<td style="padding:14px 16px; color:#222222; font-size:14px; border-top:1px solid #eee9df;">
' . ($email !== "" ? e($email) : "Not provided") . '
</td>
</tr>

<tr>
<td style="padding:14px 16px; color:#888888; font-size:13px; border-top:1px solid #eee9df;">
Phone Number
</td>
<td style="padding:14px 16px; color:#222222; font-size:14px; font-weight:bold; border-top:1px solid #eee9df;">
' . e($phone) . '
</td>
</tr>

</table>

</td>
</tr>

<!-- Event Details -->
<tr>
<td style="padding:10px 30px 20px;">

<h3 style="margin:0 0 15px; color:#222222; font-size:16px;">
Event Information
</h3>

<table width="100%" cellpadding="0" cellspacing="0"
style="background:#faf9f6; border:1px solid #eee9df; border-radius:8px;">

<tr>
<td style="padding:14px 16px; color:#888888; font-size:13px; width:35%;">
Event Type
</td>
<td style="padding:14px 16px; color:#222222; font-size:14px; font-weight:bold;">
' . ($event_type !== "" ? e($event_type) : "Not provided") . '
</td>
</tr>

<tr>
<td style="padding:14px 16px; color:#888888; font-size:13px; border-top:1px solid #eee9df;">
Event Date
</td>
<td style="padding:14px 16px; color:#b18b43; font-size:14px; font-weight:bold; border-top:1px solid #eee9df;">
' . e($formatted_date) . '
</td>
</tr>

<tr>
<td style="padding:14px 16px; color:#888888; font-size:13px; border-top:1px solid #eee9df;">
City
</td>
<td style="padding:14px 16px; color:#222222; font-size:14px; border-top:1px solid #eee9df;">
' . ($city !== "" ? e($city) : "Not provided") . '
</td>
</tr>

<tr>
<td style="padding:14px 16px; color:#888888; font-size:13px; border-top:1px solid #eee9df;">
Venue / Address
</td>
<td style="padding:14px 16px; color:#222222; font-size:14px; border-top:1px solid #eee9df;">
' . ($address !== "" ? nl2br(e($address)) : "Not provided") . '
</td>
</tr>

</table>

</td>
</tr>

<!-- Additional Message -->
<tr>
<td style="padding:10px 30px 30px;">

<h3 style="margin:0 0 15px; color:#222222; font-size:16px;">
Additional Message
</h3>

<div style="background:#faf9f6; border-left:3px solid #c5a15b; padding:18px; color:#555555; font-size:14px; line-height:24px; border-radius:4px;">
' . ($message !== "" ? nl2br(e($message)) : "No additional message provided.") . '
</div>

</td>
</tr>

<!-- Footer -->
<tr>
<td style="background:#171717; padding:25px 30px; text-align:center;">

<p style="margin:0 0 8px; color:#d6b46a; font-size:15px; font-weight:bold;">
Moments by MQlus
</p>

<p style="margin:0; color:#bbbbbb; font-size:12px; line-height:21px;">
Your moments, our passion.<br>
This is an automated booking notification from your website.
</p>

<p style="margin:15px 0 0; color:#888888; font-size:11px;">
&copy; ' . date("Y") . ' Moments by MQlus. All rights reserved.
</p>

</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>
';

// Email Headers
$headers = [
    "From: Moments by MQlus <connect@mqlus.in>",
    "MIME-Version: 1.0",
    "Content-Type: text/html; charset=UTF-8",
    "Bcc: " . implode(",", $recipients)
];

// Reply to Customer
if ($email !== "") {
    $headers[] = "Reply-To: " . $email;
}

// Send Email
if (!mail($recipient, $subject, $html, implode("\r\n", $headers))) {

    error_log("Booking notification email could not be sent.");

    send_json(
        false,
        "We couldn't send your request right now. Please try again or email us at connect@mqlus.in.",
        500
    );
}

// Success Response
send_json(true, "Booking request sent successfully!");

?>
