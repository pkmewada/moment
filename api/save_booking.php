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

$recipients = [
    "contact@mqlus.in",
    "hr@mqlus.in",
    "pp8754352@gmail.com"
];
$subject = "New booking request - Moments by MQlus";
$escape = function ($value) {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
};
$displayDate = DateTime::createFromFormat("!Y-m-d", $event_date)->format("l, d F Y");
$logoUrl = "https://momentsbymqlus.com/images/logo2.png";
$rows = [
    ["Email", $email !== "" ? $email : "Not provided"],
    ["Phone", $phone],
    ["Event type", $event_type !== "" ? $event_type : "Not provided"],
    ["City", $city !== "" ? $city : "Not provided"],
    ["Event date", $displayDate],
    ["Address / venue", $address !== "" ? $address : "Not provided"]
];

$detailsHtml = "";
foreach ($rows as $index => $row) {
    $background = $index % 2 === 0 ? "#f7f8fc" : "#ffffff";
    $detailsHtml .= '<tr><td style="padding:13px 16px;background:' . $background . ';color:#697386;font-size:14px;border-bottom:1px solid #e9edf4;width:36%;">' .
        $escape($row[0]) . '</td><td style="padding:13px 16px;background:' . $background . ';color:#171a2b;font-size:14px;font-weight:600;border-bottom:1px solid #e9edf4;">' .
        nl2br($escape($row[1])) . '</td></tr>';
}

$htmlBody = '<!doctype html><html><body style="margin:0;padding:0;background:#f2f4f8;font-family:Arial,Helvetica,sans-serif;color:#171a2b;">' .
    '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f2f4f8;padding:32px 12px;"><tr><td align="center">' .
    '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:620px;background:#ffffff;border-radius:16px;overflow:hidden;">' .
    '<tr><td style="padding:26px 32px;background:#090a16;border-bottom:4px solid #00cbd7;">' .
    '<img src="' . $logoUrl . '" width="180" height="60" alt="Moments by MQlus" style="display:block;width:180px;max-width:100%;height:auto;padding:8px 12px;background:#ffffff;border:0;border-radius:8px;margin:0 0 18px;color:#090a16;">' .
    '<div style="font-size:11px;line-height:1.4;letter-spacing:2px;text-transform:uppercase;color:#9da4b8;">New website enquiry</div>' .
    '<div style="margin-top:7px;font-size:25px;line-height:1.25;font-weight:700;color:#ffffff;">Booking request received</div>' .
    '</td></tr><tr><td style="padding:28px 32px 18px;">' .
    '<div style="font-size:14px;line-height:1.6;color:#697386;">A new booking enquiry has arrived. Here are the details:</div>' .
    '<div style="margin-top:18px;padding:17px 19px;background:#e9fbfc;border-left:4px solid #00cbd7;border-radius:8px;font-size:20px;line-height:1.35;font-weight:700;color:#101525;">' .
    $escape($name) . '</div></td></tr>' .
    '<tr><td style="padding:0 32px 24px;"><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #e9edf4;border-radius:10px;overflow:hidden;">' .
    $detailsHtml .
    '</table></td></tr>' .
    '<tr><td style="padding:0 32px 28px;"><div style="margin-bottom:9px;font-size:12px;letter-spacing:1px;font-weight:700;text-transform:uppercase;color:#697386;">Additional details</div>' .
    '<div style="padding:16px;background:#f7f8fc;border-radius:9px;font-size:14px;line-height:1.7;color:#30364a;">' .
    ($message !== "" ? nl2br($escape($message)) : "No additional details provided.") .
    '</div></td></tr>' .
    '<tr><td style="padding:18px 32px;background:#090a16;color:#9da4b8;font-size:12px;line-height:1.6;">Sent from the <strong style="color:#ffffff;">Moments by MQlus</strong> website booking form.<br>' .
    '<a href="mailto:' . $escape($email !== "" ? $email : "connect@mqlus.in") . '" style="color:#00d8e4;text-decoration:none;">Reply to this enquiry</a></td></tr>' .
    '</table></td></tr></table></body></html>';

$plainBody = "New booking request - Moments by MQlus\r\n\r\n" .
    "Name: " . $name . "\r\n" .
    "Email: " . ($email !== "" ? $email : "Not provided") . "\r\n" .
    "Phone: " . $phone . "\r\n" .
    "Event type: " . ($event_type !== "" ? $event_type : "Not provided") . "\r\n" .
    "City: " . ($city !== "" ? $city : "Not provided") . "\r\n" .
    "Event date: " . $displayDate . "\r\n" .
    "Address / venue: " . ($address !== "" ? $address : "Not provided") . "\r\n\r\n" .
    "Additional details:\r\n" . ($message !== "" ? $message : "None");

$boundary = "moments_" . bin2hex(random_bytes(16));
$body = "--" . $boundary . "\r\n" .
    "Content-Type: text/plain; charset=UTF-8\r\n" .
    "Content-Transfer-Encoding: 8bit\r\n\r\n" .
    $plainBody . "\r\n\r\n" .
    "--" . $boundary . "\r\n" .
    "Content-Type: text/html; charset=UTF-8\r\n" .
    "Content-Transfer-Encoding: 8bit\r\n\r\n" .
    $htmlBody . "\r\n\r\n" .
    "--" . $boundary . "--\r\n";

$headers = [
    "From: Moments by MQlus <connect@mqlus.in>",
    "MIME-Version: 1.0",
    "Content-Type: multipart/alternative; boundary=\"" . $boundary . "\""
];

if ($email !== "") {
    $headers[] = "Reply-To: " . $email;
}

if (!mail(implode(", ", $recipients), $subject, $body, implode("\r\n", $headers))) {
    error_log("Booking notification email could not be sent.");
    send_json(
        false,
        "We couldn't send your request right now. Please try again or email us at connect@mqlus.in.",
        500
    );
}

send_json(true, "Booking request sent successfully!");

?>
