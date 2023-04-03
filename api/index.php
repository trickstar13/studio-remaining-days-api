<?php
header('Content-Type: application/json');

// Get input parameters
$future_date = isset($_GET['future_date']) ? $_GET['future_date'] : null;
$timezone = isset($_GET['timezone']) ? $_GET['timezone'] : 'UTC';
$repeat_annually = isset($_GET['repeat_annually']) ? filter_var($_GET['repeat_annually'], FILTER_VALIDATE_BOOLEAN) : false;

// Validate input parameters
if (!$future_date || !strtotime($future_date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid future_date parameter']);
    exit;
}

if (!in_array($timezone, timezone_identifiers_list())) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid timezone parameter']);
    exit;
}

// Set timezone
date_default_timezone_set($timezone);

// Calculate remaining days
$today =  new DateTime('today midnight');
$future = new DateTime($future_date);
$interval = $today->diff($future);

if ($repeat_annually && $future < $today) {
    while ($future < $today) {
        $future->modify('+1 year');
    }
    $interval = $today->diff($future);
}

$remaining_days = $interval->format('%R%a');

if (!$repeat_annually && $remaining_days < 0) {
    $remaining_days = 0;
}

// Split remaining_days into digits and add keys
$remaining_days_digits = str_split($remaining_days);
$remaining_days_digits = array_map('intval', $remaining_days_digits);
$remaining_days_digits_with_keys = [];

for ($i = 0; $i < count($remaining_days_digits); $i++) {
    $key = "digit_" . (10 ** (count($remaining_days_digits) - $i - 1));
    $remaining_days_digits_with_keys[$key] = $remaining_days_digits[$i];
}

// Ensure the array has exactly 5 elements, pad with zeros
$keys = ['digit_10000', 'digit_1000', 'digit_100', 'digit_10', 'digit_1'];
$remaining_days_digits_with_keys = array_replace(array_flip($keys), $remaining_days_digits_with_keys);

// Send response
echo json_encode([
    'data' => [
        [
            'remaining_days' => (int)$remaining_days,
            'remaining_days_digits' => $remaining_days_digits_with_keys
        ]
    ]
]);
