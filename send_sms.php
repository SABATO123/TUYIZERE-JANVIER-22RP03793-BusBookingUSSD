<?php
require_once __DIR__ . '/vendor/autoload.php';

// Africa's Talking API credentials
$username = "sandbox"; // Change to your Africa's Talking username
$apiKey = "atsk_8066c257f806f88e6a7793ff33228d0110ed34ccd6389a112b24fd13f0be981dde2f2313"; // Change to your Africa's Talking API key
$senderId = "MyMoney ltd"; // Sender ID for SMS

// Initialize the SDK
$AT = new AfricasTalking\SDK\AfricasTalking($username, $apiKey);
// Get the SMS service
$sms = $AT->sms();

function sendSMS($phoneNumber, $message) {
    global $sms;
    try {
        $result = $sms->send([
            'to'      => $phoneNumber,
            'message' => $message
            // 'from' => 'MyMoney ltd' // Only use this in production if approved
        ]);
        return $result;
    } catch (Exception $e) {
        // Log or handle the error
        file_put_contents('sms_debug.log', $e->getMessage() . PHP_EOL, FILE_APPEND);
        return false;
    }
}

// Function to send booking confirmation SMS
function sendBookingConfirmation($phoneNumber, $bookingDetails) {
    $message = "Your bus booking has been confirmed!\n";
    $message .= "Route: " . $bookingDetails['from_location'] . " to " . $bookingDetails['to_location'] . "\n";
    $message .= "Date: " . $bookingDetails['booking_date'] . "\n";
    $message .= "Seat: " . $bookingDetails['seat_number'] . "\n";
    $message .= "Thank you for choosing our service!";
    
    return sendSMS($phoneNumber, $message);
}
?> 
