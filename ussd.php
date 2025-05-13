<?php
require_once 'config.php';

// Get the POST data from Africa's Talking
$sessionId   = $_POST['sessionId'] ?? '';
$serviceCode = $_POST['serviceCode'] ?? '';
$phoneNumber = $_POST['phoneNumber'] ?? '';
$text        = $_POST['text'] ?? '';

// Initialize the response
$response = "";

// Split the text into an array
$textArray = explode("*", $text);
$userLevel = count($textArray);

// Main menu
if ($text == "") {
    $response = "CON Welcome to Bus Booking Service\n";
    $response .= "1. View Available Routes\n";
    $response .= "2. My Bookings\n";
    $response .= "3. Help";
}


// Handle user selection
else if ($text == "1") {
    $response = "CON Select Route:\n";
    
    $sql = "SELECT route_id, from_location, to_location, departure_time, price FROM routes WHERE available_seats > 0";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $counter = 1;
        while($row = $result->fetch_assoc()) {
            $response .= $counter . ". " . $row['from_location'] . " to " . $row['to_location'] . 
                        " (" . $row['departure_time'] . ") - RWF " . $row['price'] . "\n";
            $counter++;
        }
    } else {
        $response = "END No routes available at the moment.";
    }
}

// Handle route selection
else if ($userLevel == 2 && $textArray[0] == "1") {
    $selectedRoute = $textArray[1];
    // Generate next 5 dates
    $dates = [];
    for ($i = 0; $i < 5; $i++) {
        $dates[] = date('Y-m-d', strtotime("+{$i} day"));
    }
    $response = "CON Select Date:\n";
    foreach ($dates as $idx => $date) {
        $response .= ($idx + 1) . ". " . $date . "\n";
    }
}

// Handle date selection (user picks 1-5)
else if ($userLevel == 3 && $textArray[0] == "1") {
    $selectedRoute = $textArray[1];
    $selectedDateIndex = intval($textArray[2]);
    // Generate next 5 dates
    $dates = [];
    for ($i = 0; $i < 5; $i++) {
        $dates[] = date('Y-m-d', strtotime("+{$i} day"));
    }
    if ($selectedDateIndex < 1 || $selectedDateIndex > 5) {
        $response = "END Invalid date selection. Please try again.";
    } else {
        $selectedDate = $dates[$selectedDateIndex - 1];
        $response = "CON Select Seat Number:\n";
        $response .= "Enter seat number (1-30)";
    }
}

// Handle seat selection
else if ($userLevel == 4 && $textArray[0] == "1") {
    $selectedRoute = $textArray[1];
    $selectedDate = $textArray[2];
    $selectedSeat = $textArray[3];
    
    // Validate seat number
    if ($selectedSeat < 1 || $selectedSeat > 30) {
        $response = "END Invalid seat number. Please select between 1-30";
    } else {
        $response = "CON Confirm Booking:\n";
        $response .= "Route: " . $selectedRoute . "\n";
        $response .= "Date: " . $selectedDate . "\n";
        $response .= "Seat: " . $selectedSeat . "\n";
        $response .= "1. Confirm\n";
        $response .= "2. Cancel";
    }
}

// Handle booking confirmation
else if ($userLevel == 5 && $textArray[0] == "1") {
    $selectedRoute = $textArray[1];
    $selectedDateIndex = intval($textArray[2]);
    $selectedSeat = $textArray[3];
    $confirmation = $textArray[4];
    
    // Generate next 5 dates
    $dates = [];
    for ($i = 0; $i < 5; $i++) {
        $dates[] = date('Y-m-d', strtotime("+{$i} day"));
    }
    $selectedDate = $dates[$selectedDateIndex - 1];
    
    if ($confirmation == "1") {
        // Insert booking into database
        $sql = "INSERT INTO bookings (phone_number, route_id, seat_number, booking_date) 
                VALUES ('$phoneNumber', '$selectedRoute', '$selectedSeat', '$selectedDate')";
        
        if ($conn->query($sql) === TRUE) {
            // Update available seats
            $sql = "UPDATE routes SET available_seats = available_seats - 1 WHERE route_id = '$selectedRoute'";
            $conn->query($sql);
            
            // Fetch route details for SMS
            $routeSql = "SELECT from_location, to_location FROM routes WHERE route_id = '$selectedRoute'";
            $routeResult = $conn->query($routeSql);
            $routeDetails = $routeResult->fetch_assoc();
            
            // Prepare booking details for SMS
            $bookingDetails = array(
                'from_location' => $routeDetails['from_location'],
                'to_location' => $routeDetails['to_location'],
                'booking_date' => $selectedDate,
                'seat_number' => $selectedSeat
            );
            // Send SMS confirmation
            require_once 'send_sms.php';
            sendBookingConfirmation($phoneNumber, $bookingDetails);
            
            $response = "END Booking confirmed! You will receive an SMS with your booking details.";
        } else {
            $response = "END Error processing booking. Please try again.";
        }
    } else {
        $response = "END Booking cancelled.";
    }
}

// Handle My Bookings
else if ($text == "2") {
    $sql = "SELECT b.*, r.from_location, r.to_location, r.departure_time 
            FROM bookings b 
            JOIN routes r ON b.route_id = r.route_id 
            WHERE b.phone_number = '$phoneNumber'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $response = "CON Your Bookings:\n";
        $bookingDetails = [];
        while($row = $result->fetch_assoc()) {
            $response .= "Route: " . $row['from_location'] . " to " . $row['to_location'] . "\n";
            $response .= "Date: " . $row['booking_date'] . "\n";
            $response .= "Seat: " . $row['seat_number'] . "\n";
            $response .= "Status: " . $row['status'] . "\n\n";
            $bookingDetails[] = $row;
        }
        // Send SMS with booking details
        require_once 'send_sms.php';
        $message = "Your booking details:\n";
        foreach ($bookingDetails as $booking) {
            $message .= "Route: " . $booking['from_location'] . " to " . $booking['to_location'] . "\n";
            $message .= "Date: " . $booking['booking_date'] . "\n";
            $message .= "Seat: " . $booking['seat_number'] . "\n";
            $message .= "Status: " . $booking['status'] . "\n\n";
        }
        sendSMS($phoneNumber, $message);
    } else {
        $response = "END You have no bookings.";
    }
}

// Handle Help
else if ($text == "3") {
    $response = "END For assistance, please call our customer service at +250784938852";
}

// Send the response back to Africa's Talking
header('Content-type: text/plain');
echo $response;
?> 