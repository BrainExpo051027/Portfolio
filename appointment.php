<?php
// Basic email handler for appointment requests
// Configure your recipient email
$to = 'you@example.com'; // TODO: change to your email
$saveFallbackPath = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'appointments.csv';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'message' => 'Method not allowed']);
  exit;
}

// Simple honeypot
if (!empty($_POST['website'])) {
  echo json_encode(['ok' => true, 'message' => 'Thank you!']);
  exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$date = trim($_POST['date'] ?? '');
$time = trim($_POST['time'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $date === '' || $time === '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'message' => 'Please fill in all fields']);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'message' => 'Invalid email']);
  exit;
}

$subject = 'New Appointment Request';
$body = "Name: $name\nEmail: $email\nPreferred: $date $time\nMessage: $message\n";
$headers = "From: noreply@" . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "\r\n" .
           "Reply-To: $email\r\n" .
           "Content-Type: text/plain; charset=UTF-8\r\n";

$sent = false;
if (function_exists('mail')) {
  $sent = @mail($to, $subject, $body, $headers);
}

if ($sent) {
  echo json_encode(['ok' => true, 'message' => 'Appointment request sent. I will get back to you.']);
  exit;
}

// Fallback: save to CSV so requests are not lost during local setup
$saved = false;
try {
  if (!is_dir(dirname($saveFallbackPath))) {
    @mkdir(dirname($saveFallbackPath), 0775, true);
  }
  $isNew = !file_exists($saveFallbackPath);
  $fh = fopen($saveFallbackPath, 'a');
  if ($fh) {
    if ($isNew) {
      fputcsv($fh, ['timestamp', 'name', 'email', 'date', 'time', 'message']);
    }
    fputcsv($fh, [date('c'), $name, $email, $date, $time, $message]);
    fclose($fh);
    $saved = true;
  }
} catch (\Throwable $e) {
  // ignore
}

if ($saved) {
  echo json_encode(['ok' => true, 'message' => 'Email not configured. Saved your request locally (appointments.csv).']);
  exit;
}

http_response_code(500);
echo json_encode(['ok' => false, 'message' => 'Failed to send email. Configure SMTP in php.ini or use PHPMailer.']);


